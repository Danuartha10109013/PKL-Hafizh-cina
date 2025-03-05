import face_recognition
import numpy as np
import sys
import os

def load_known_faces(acuan_folder):
    known_encodings = []
    known_names = []

    if not os.path.exists(acuan_folder):
        print("Error: Acuan folder not found")
        sys.exit(1)

    for filename in os.listdir(acuan_folder):
        if filename.endswith(".jpg") or filename.endswith(".png"):
            path = os.path.join(acuan_folder, filename)
            image = face_recognition.load_image_file(path)
            encodings = face_recognition.face_encodings(image)

            if len(encodings) > 0:
                known_encodings.append(encodings[0])
                known_names.append(os.path.splitext(filename)[0])

    if not known_encodings:
        print("Error: No faces found in reference images")
        sys.exit(1)

    return known_encodings, known_names

def recognize_face(image_path, acuan_folder):
    if not os.path.exists(image_path):
        print("Error: Attendance image not found")
        sys.exit(1)

    known_encodings, known_names = load_known_faces(acuan_folder)

    unknown_image = face_recognition.load_image_file(image_path)
    encodings = face_recognition.face_encodings(unknown_image)

    if len(encodings) == 0:
        print("Error: No face detected in attendance image")
        sys.exit(1)

    unknown_encoding = encodings[0]

    # Bandingkan dengan semua wajah di folder acuan
    distances = face_recognition.face_distance(known_encodings, unknown_encoding)

    # Cari wajah dengan jarak terkecil
    best_match_index = np.argmin(distances)
    threshold = 0.5  # Sesuaikan nilai threshold jika perlu

    if distances[best_match_index] < threshold:
        print(known_names[best_match_index])
    else:
        print("Unknown")

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Error: Invalid arguments")
        sys.exit(1)

    image_path = sys.argv[1]
    acuan_folder = sys.argv[2]  # Sekarang ini adalah folder, bukan satu file

    recognize_face(image_path, acuan_folder)
