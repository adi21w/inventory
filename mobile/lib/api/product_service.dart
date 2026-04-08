// lib/api/product_service.dart
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import '../models/product_model.dart';
import '../config/constants.dart';

class ProductService {
  final storage = const FlutterSecureStorage();

  Future<List<ProductModel>> fetchProducts() async {
    String? token = await storage.read(key: 'jwt_token');

    try {
      final response = await http.get(
        Uri.parse(AppConfig.productEndpoint),
        headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        },
      );

      if (response.statusCode == 200) {
        List<dynamic> data = jsonDecode(response.body);
        // Mapping dari JSON ke List<ProductModel>
        return data.map((json) => ProductModel.fromJson(json)).toList();
      } else {
        throw Exception('Gagal load produk');
      }
    } catch (e) {
      throw Exception('Gagal load produk: $e');
    }
  }

  // lib/api/product_service.dart
  Future<Map<String, dynamic>> createProduct(
    Map<String, dynamic> formData,
  ) async {
    String? token = await storage.read(key: 'jwt_token');
    try {
      final response = await http.post(
        Uri.parse(AppConfig.productEndpoint),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
        body: jsonEncode(formData),
      );
      final data = jsonDecode(response.body);
      return {
        'success': response.statusCode == 201 || response.statusCode == 200,
        'message': data['message'] ?? 'Produk berhasil disimpan',
      };
    } catch (e) {
      return {'success': false, 'message': 'Terjadi kesalahan koneksi'};
    }
  }

  // Ambil 1 produk detail
  Future<ProductModel?> fetchProductDetail(int id) async {
    String? token = await storage.read(key: 'jwt_token');
    final response = await http.get(
      Uri.parse("${AppConfig.productEndpoint}/$id"),
      headers: {'Authorization': 'Bearer $token', 'Accept': 'application/json'},
    );

    if (response.statusCode == 200) {
      return ProductModel.fromJson(jsonDecode(response.body));
    }
    return null;
  }

  // Update produk
  Future<Map<String, dynamic>> updateProduct(
    int id,
    Map<String, dynamic> formData,
  ) async {
    String? token = await storage.read(key: 'jwt_token');
    try {
      final response = await http.patch(
        // Atau .patch sesuai Yii2
        Uri.parse("${AppConfig.productEndpoint}/$id"),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
        body: jsonEncode(formData),
      );
      final data = jsonDecode(response.body);
      return {
        'success': response.statusCode == 200,
        'message': data['message'] ?? 'Update Success',
      };
    } catch (e) {
      return {'success': false, 'message': 'Connection Error'};
    }
  }
}
