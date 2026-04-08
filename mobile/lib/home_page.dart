// lib/home_page.dart
import 'package:flutter/material.dart';
import 'api_service.dart'; // Import service yang kita buat tadi

class HomePage extends StatefulWidget {
  const HomePage({super.key});

  @override
  State<HomePage> createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  final ApiService _apiService = ApiService();
  String _data = "Sedang mengambil data...";

  @override
  void initState() {
    super.initState();
    // Panggil fungsi ambil data pas halaman pertama kali dibuka
    _loadData();
  }

  Future<void> _loadData() async {
    final result = await _apiService.fetchDataRahasia();

    if (result != null) {
      setState(() {
        _data = result['message'] ?? "Data kosong";
      });
    } else {
      setState(() {
        _data = "Gagal mengambil data. Mungkin token expired?";
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Dashboard')),
      body: Center(child: Text(_data, style: const TextStyle(fontSize: 18))),
    );
  }
}
