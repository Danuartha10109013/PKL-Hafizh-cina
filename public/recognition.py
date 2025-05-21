import face_recognition
import numpy as np
import sys
import os

# Fungsi untuk memuat wajah-wajah yang dikenal dari folder acuan
def load_known_faces(acuan_folder):
    known_encodings = []  # List untuk menyimpan encoding wajah
    known_names = []      # List untuk menyimpan nama file (sebagai nama orang)

    # Cek apakah folder acuan tersedia
    if not os.path.exists(acuan_folder):
        print("Error: Acuan folder not found")
        sys.exit(1)

    # Loop semua file gambar dalam folder
    for filename in os.listdir(acuan_folder):
        if filename.endswith(".jpg") or filename.endswith(".png"):
            path = os.path.join(acuan_folder, filename)
            image = face_recognition.load_image_file(path)           # Baca gambar
            encodings = face_recognition.face_encodings(image)       # Ambil encoding wajah

            # Jika ada wajah terdeteksi, simpan encoding dan nama file (tanpa ekstensi)
            if len(encodings) > 0:
                known_encodings.append(encodings[0])
                known_names.append(os.path.splitext(filename)[0])

    # Jika tidak ada wajah sama sekali di folder referensi, keluarkan error
    if not known_encodings:
        print("Error: No faces found in reference images")
        sys.exit(1)

    return known_encodings, known_names

# Fungsi untuk mengenali wajah dari gambar absensi
def recognize_face(image_path, acuan_folder):
    # Cek apakah gambar absensi tersedia
    if not os.path.exists(image_path):
        print("Error: Attendance image not found")
        sys.exit(1)

    # Muat data wajah yang dikenal dari folder referensi
    known_encodings, known_names = load_known_faces(acuan_folder)

    # Baca gambar absensi
    unknown_image = face_recognition.load_image_file(image_path)
    encodings = face_recognition.face_encodings(unknown_image)

    # Jika tidak ada wajah terdeteksi, keluarkan error
    if len(encodings) == 0:
        print("Error: No face detected in attendance image")
        sys.exit(1)

    unknown_encoding = encodings[0]  # Ambil encoding wajah pertama

    # Hitung jarak antara wajah absensi dengan semua wajah referensi
    distances = face_recognition.face_distance(known_encodings, unknown_encoding)

    # Cari indeks dengan jarak terkecil (wajah yang paling mirip)
    best_match_index = np.argmin(distances)

    threshold = 0.5  # Nilai ambang batas kemiripan (semakin kecil = lebih ketat)

    # Jika jaraknya lebih kecil dari threshold, anggap cocok
    if distances[best_match_index] < threshold:
        print(known_names[best_match_index])  # Tampilkan nama yang cocok
    else:
        print("Unknown")  # Jika tidak cocok, tampilkan "Unknown"

# Fungsi utama jika dijalankan lewat command line
if __name__ == "__main__":
    # Program harus menerima 2 argumen: path gambar absensi dan folder acuan
    if len(sys.argv) != 3:
        print("Error: Invalid arguments")
        sys.exit(1)

    image_path = sys.argv[1]       # Gambar wajah absensi
    acuan_folder = sys.argv[2]     # Folder berisi wajah-wajah referensi

    recognize_face(image_path, acuan_folder)  # Jalankan proses pengenalan wajah
