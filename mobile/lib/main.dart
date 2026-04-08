import 'package:flutter/material.dart';
import 'pages/auth/login_page.dart';
import 'pages/home/home_page.dart';
import 'pages/pack/pack_page.dart'; // Import halaman baru lo
import 'pages/pack/pack_form_page.dart'; // Import halaman baru lo

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Inventory App',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        primaryColor: const Color(0xFF56C7CD),
        useMaterial3: true,
      ),

      // 1. Tentukan halaman mana yang muncul pertama kali
      initialRoute: '/',

      // 2. Daftar rute aplikasi lo di sini
      routes: {
        '/': (context) => LoginPage(), // Halaman Login
        '/home': (context) => const HomePage(), // Halaman Dashboard
        '/packs': (context) => const PackPage(), // Halaman List Kemasan
        '/packs-add': (context) => PackFormPage(), // Halaman Tambah Kemasan
      },
    );
  }
}
