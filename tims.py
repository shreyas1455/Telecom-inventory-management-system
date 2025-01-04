import pandas as pd
import numpy as np
from sklearn.preprocessing import MinMaxScaler
from sklearn.metrics import mean_absolute_percentage_error, r2_score
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import LSTM, GRU, Dense, Dropout
from tensorflow.keras.optimizers import Adam
from tensorflow.keras.callbacks import ReduceLROnPlateau

class Preprocess:
    def _init_(self, file_path, sheet_name):
        self.file_path = file_path
        self.sheet_name = sheet_name
        self.data = None
        self.time_series_data = None

    def load_data(self):
        """Load data from the specified Excel sheet."""
        excel_file = pd.ExcelFile(self.file_path)
        self.data = excel_file.parse(self.sheet_name)

    def clean_and_prepare(self):
        """Parse order dates, clean data, and prepare for time-series analysis."""
        # Parse dates and handle invalid entries
        self.data['Order Date'] = pd.to_datetime(self.data['Order Date'], errors='coerce')

        # Drop rows with invalid or missing dates
        self.data = self.data.dropna(subset=['Order Date'])

        # Sort data by order date
        self.data = self.data.sort_values(by='Order Date')

    def aggregate_data(self):
        """Aggregate quantities by order date for time-series analysis."""
        # Extract relevant columns
        time_series_data = self.data[['Order Date', 'Quantity']]

        # Aggregate quantities by order date
        self.time_series_data = time_series_data.groupby('Order Date').sum().reset_index()

    def get_time_series_data(self):
        """Return the prepared time-series data."""
        return self.time_series_data

class Forecast:
    def _init_(self, time_series_data):
        self.time_series_data = time_series_data
        self.model = None
        self.scaler = MinMaxScaler(feature_range=(0, 1))

    def prepare_data(self, sequence_length=10):
        """Prepare data for LSTM forecasting."""
        # Scale the quantity data
        quantity_scaled = self.scaler.fit_transform(self.time_series_data['Quantity'].values.reshape(-1, 1))

        # Create sequences
        X, y = [], []
        for i in range(len(quantity_scaled) - sequence_length):
            X.append(quantity_scaled[i:i + sequence_length])
            y.append(quantity_scaled[i + sequence_length])
        return np.array(X), np.array(y)

    def build_model(self, sequence_length):
        """Build the LSTM/GRU model."""
        self.model = Sequential([
            GRU(50, activation='relu', return_sequences=True, input_shape=(sequence_length, 1)),
            Dropout(0.2),
            GRU(50, activation='relu', return_sequences=False),
            Dropout(0.3),
            Dense(25, activation='relu'),
            Dense(1)
        ])
        optimizer = Adam(learning_rate=0.0001)
        self.model.compile(optimizer=optimizer, loss='mse')

    def train_model(self, X_train, y_train, X_test, y_test, epochs=50, batch_size=16):
        """Train the model."""
        # Add learning rate scheduler
        lr_scheduler = ReduceLROnPlateau(monitor='val_loss', factor=0.5, patience=5, verbose=1)

        self.model.fit(X_train, y_train, validation_data=(X_test, y_test), epochs=epochs, batch_size=batch_size, verbose=1, callbacks=[lr_scheduler])

    def evaluate_model(self, X_train, y_train, X_test, y_test):
        """Evaluate the model performance and calculate accuracy."""
        # Evaluate losses
        train_loss = self.model.evaluate(X_train, y_train, verbose=0)
        test_loss = self.model.evaluate(X_test, y_test, verbose=0)

        # Predict on train and test sets
        y_train_pred = self.model.predict(X_train)
        y_test_pred = self.model.predict(X_test)

        # Inverse transform predictions and true values to original scale
        y_train_pred = self.scaler.inverse_transform(y_train_pred)
        y_test_pred = self.scaler.inverse_transform(y_test_pred)
        y_train = self.scaler.inverse_transform(y_train.reshape(-1, 1))
        y_test = self.scaler.inverse_transform(y_test.reshape(-1, 1))

        # Calculate accuracy metrics (MAPE and R-squared)
        train_mape = mean_absolute_percentage_error(y_train, y_train_pred)
        test_mape = mean_absolute_percentage_error(y_test, y_test_pred)
        test_r2 = r2_score(y_test, y_test_pred)

        print(f"Training Loss: {train_loss}")
        print(f"Testing Loss: {test_loss}")
        print(f"Training MAPE: {train_mape * 100:.2f}%")
        print(f"Testing MAPE: {test_mape * 100:.2f}%")
        print(f"Testing R-squared: {test_r2:.2f}")


# Example Usage:
# Preprocessing
preprocess = Preprocess(file_path='Inventory_DataSet.xlsx', sheet_name='Product')
preprocess.load_data()
preprocess.clean_and_prepare()
preprocess.aggregate_data()
time_series_data = preprocess.get_time_series_data()

# Forecasting
forecast = Forecast(time_series_data)
sequence_length = 15
X, y = forecast.prepare_data(sequence_length)

# Split data into training and testing sets
split_index = int(len(X) * 0.8)
X_train, X_test = X[:split_index], X[split_index:]
y_train, y_test = y[:split_index], y[split_index:]

# Build, train, and evaluate the model
forecast.build_model(sequence_length)
forecast.train_model(X_train, y_train, X_test, y_test, epochs=100, batch_size=16)
forecast.evaluate_model(X_train, y_train, X_test, y_test)