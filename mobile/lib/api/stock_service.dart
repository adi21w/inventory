import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import '../models/stock_model.dart'; // Pastikan path model benar
import '../config/constants.dart';

class StockService {
  final storage = const FlutterSecureStorage();

  Future<List<StockModel>> fetchStocks() async {
    String? token = await storage.read(key: 'jwt_token');

    try {
      final response = await http.get(
        Uri.parse(AppConfig.stockEndpoint),
        headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        },
      );

      if (response.statusCode == 200) {
        List<dynamic> body = jsonDecode(response.body);
        return body.map((item) => StockModel.fromJson(item)).toList();
      } else {
        throw Exception('Failed to load stock');
      }
    } catch (e) {
      print("Error StockService: $e");
      return [];
    }
  }
}
