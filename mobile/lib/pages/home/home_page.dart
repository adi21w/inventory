// lib/home_page.dart
import 'package:flutter/material.dart';
import '../../api/profile_service.dart'; // Import service yang kita buat tadi
import '../layout/main_layout.dart';

class HomePage extends StatefulWidget {
  const HomePage({super.key});

  @override
  State<HomePage> createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  final ProfileService _profileService = ProfileService();

  dynamic _userData;
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  Future<void> _loadData() async {
    final result = await _profileService.fetchDataRahasia();

    if (result != null) {
      setState(() {
        _userData = result['user'];
        _isLoading = false;
      });
    } else {
      setState(() {
        _userData = null;
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return MainLayout(
      title: "Dashboard Inventory",
      content: Column(
        crossAxisAlignment: CrossAxisAlignment.start, // Biar teks rata kiri
        children: [
          Padding(
            padding: EdgeInsets.all(16.0),
            child: _isLoading
                ? const CircularProgressIndicator()
                : _userData != null
                ? Text(
                    "Selamat Datang, ${_userData['name']}",
                    style: TextStyle(
                      fontSize: 20,
                      fontWeight: FontWeight.bold,
                      color: const Color.fromARGB(255, 108, 117, 117),
                    ),
                  ) // PAKAI KURUNG KURAWAL {}
                : const Text("Gagal mengambil data atau token expired"),
          ),
        ],
      ),
    );
  }
}
