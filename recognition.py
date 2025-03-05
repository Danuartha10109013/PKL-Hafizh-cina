import face_recognition
import os
import numpy as np

# Load known images
known_faces = []
known_names = []

for file in os.listdir("known_faces"):
    image = face_recognition.load_image_file(f"known_faces/{file}")
    encodings = face_recognition.face_encodings(image)

    if len(encodings) > 0:  # Pastikan ada wajah yang terdeteksi
        known_faces.append(encodings[0])
        known_names.append(file.split(".")[0])

# Process new image
def recognize_face(image_path):
    unknown_image = face_recognition.load_image_file(image_path)
    encodings = face_recognition.face_encodings(unknown_image)

    if len(encodings) == 0:
        return "No Face Detected"  # Jika tidak ada wajah

    unknown_encoding = encodings[0]
    
    # Gunakan face distance untuk mengetahui tingkat kemiripan
    face_distances = face_recognition.face_distance(known_faces, unknown_encoding)
    best_match_index = np.argmin(face_distances)  # Ambil indeks dengan kemiripan tertinggi

    # Tetapkan threshold (misalnya 0.5, semakin kecil semakin ketat)
    threshold = 0.5
    if face_distances[best_match_index] < threshold:
        return known_names[best_match_index]
    else:
        return "Unknown"
