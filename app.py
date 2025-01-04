from flask import Flask, request, jsonify, render_template
import joblib
import pandas as pd

app = Flask(__name__)

model = joblib.load('models/random_forest_model.pkl')

@app.route('/')
def home():
    return render_template('index.html')

@app.route('/predict', methods=['POST'])
def predict():
    features = [float(x) for x in request.form.values()]
    final_features = pd.DataFrame([features], columns=['StockLevel', 'ReorderPoint', 'Quantity'])
    prediction = model.predict(final_features)
    output = round(prediction[0], 2)
    return render_template('index.html', prediction_text=f'Predicted Stock Level: {output}')

if __name__ == "__main__":
    app.run(debug=True)
