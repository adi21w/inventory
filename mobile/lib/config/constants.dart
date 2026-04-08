// lib/constants.dart
class AppConfig {
  // Ganti ini kalau IP server berubah
  static const String baseUrl = 'http://localhost:8080';

  // Endpoint bisa lo list di sini juga biar rapi
  static const String loginEndpoint = '$baseUrl/auth/login';
  static const String profileEndpoint = '$baseUrl/auth/profile';
  static const String productEndpoint = '$baseUrl/products';
  static const String packEndpoint = '$baseUrl/packs';
  static const String rackEndpoint = '$baseUrl/racks';
  static const String warehouseEndpoint = '$baseUrl/warehouses';
  static const String stockEndpoint = '$baseUrl/stocks';
}
