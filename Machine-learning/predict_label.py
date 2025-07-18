# Import libraries
import cv2
import numpy as np
import tensorflow as tf
import os
# Define a list of categories based on ASCII codes
CATEGORIES = [chr(i + 65) for i in range(26)] + ['spasi']

# Load the trained model
#model = tf.keras.models.load_model('./result/best_model.h5')


# Path absolut berdasarkan lokasi file Python
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
MODEL_PATH = os.path.join(BASE_DIR, "result", "best_model.h5")

print("Model path:", MODEL_PATH)  # Tambahan debug, bisa dihapus nanti

# Load model
model = tf.keras.models.load_model(MODEL_PATH)


# Define a function to predict the label of an input image
def predicted_label(input_image):
  # Rescale the image to values between 0 and 1
  img_rescale = (input_image * 1.0) / 255.0
  # Resize the image to match the model input size
  img_resize = cv2.resize(img_rescale, (224, 224))
  # Add a dimension to the image for batch processing
  img_resize_expand = img_resize.reshape(1, *img_resize.shape)
  # Predict the label using the model
  my_prediction = model.predict(img_resize_expand)
  # Get the category with the highest probability
  my_label = CATEGORIES[np.argmax(my_prediction)]
  # Return the predicted label
  return my_label
