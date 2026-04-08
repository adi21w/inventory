// lib/api_service.dart
import 'package:http/http.dart' as http;
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'dart:convert';
import '../config/constants.dart';

class PackService {
  final storage = const FlutterSecureStorage();

  Future<List<dynamic>?> fetchPacks() async {
    String? token = await storage.read(key: 'jwt_token');

    try {
      final response = await http.get(
        Uri.parse(AppConfig.packEndpoint),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      if (response.statusCode == 200) {
        return jsonDecode(response.body); // Mengembalikan List data kemasan
      }
      return null;
    } catch (e) {
      return null;
    }
  }

  Future<Map<String, dynamic>> createPack(String name) async {
    String? token = await storage.read(key: 'jwt_token');

    try {
      final response = await http.post(
        Uri.parse(AppConfig.packEndpoint),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
        body: jsonEncode({
          'pack': name,
          // Hapus capacity jika memang tidak digunakan di DB
        }),
      );

      // Ambil body response dari Yii2 (status & message)
      final Map<String, dynamic> data = jsonDecode(response.body);

      // Kita balikan utuh agar UI bisa nampilin 'message'-nya
      return {
        'success': response.statusCode == 201 || response.statusCode == 200,
        'message':
            data['message'] ??
            (response.statusCode == 201 ? 'Berhasil' : 'Gagal'),
      };
    } catch (e) {
      return {'success': false, 'message': 'Terjadi kesalahan koneksi'};
    }
  }
}
