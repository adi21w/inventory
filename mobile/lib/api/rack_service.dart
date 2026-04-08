// lib/api_service.dart
import 'package:http/http.dart' as http;
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'dart:convert';
import '../config/constants.dart';

class RackService {
  final storage = const FlutterSecureStorage();

  Future<List<dynamic>?> fetchRacks() async {
    String? token = await storage.read(key: 'jwt_token');

    try {
      final response = await http.get(
        Uri.parse(AppConfig.rackEndpoint),
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

  Future<Map<String, dynamic>> createRack(String name) async {
    String? token = await storage.read(key: 'jwt_token');

    try {
      final response = await http.post(
        Uri.parse(AppConfig.rackEndpoint),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
        body: jsonEncode({'rack': name}),
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

  // Ambil detail rack berdasarkan ID
  Future<Map<String, dynamic>?> fetchRackDetail(int id) async {
    String? token = await storage.read(key: 'jwt_token');
    try {
      final response = await http.get(
        Uri.parse("${AppConfig.rackEndpoint}/$id"), // Contoh: /racks/1
        headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        },
      );
      if (response.statusCode == 200) return jsonDecode(response.body);
      return null;
    } catch (e) {
      return null;
    }
  }

  // Update rack
  Future<Map<String, dynamic>> updateRack(int id, String name) async {
    String? token = await storage.read(key: 'jwt_token');
    try {
      final response = await http.patch(
        // Sesuai permintaan lo pakai PATCH
        Uri.parse("${AppConfig.rackEndpoint}/$id"),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
        body: jsonEncode({'rack': name}),
      );
      final data = jsonDecode(response.body);
      return {
        'success': response.statusCode == 200,
        'message': data['message'] ?? 'Update berhasil',
      };
    } catch (e) {
      return {'success': false, 'message': 'Koneksi bermasalah'};
    }
  }
}
