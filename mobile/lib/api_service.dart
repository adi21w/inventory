// lib/api_service.dart
import 'package:http/http.dart' as http;
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'dart:convert';

class ApiService {
  final storage = const FlutterSecureStorage();
  final String baseUrl = 'http://localhost:8080'; // Sesuaikan IP & Port

  Future<Map<String, dynamic>?> fetchDataRahasia() async {
    // 1. Ambil token dari storage
    String? token = await storage.read(key: 'jwt_token');

    try {
      final response = await http.get(
        Uri.parse('$baseUrl/auth/profile'),

        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      } else {
        print('Error: ${response.statusCode}');
        return null;
      }
    } catch (e) {
      print('Exception: $e');
      return null;
    }
  }
}
