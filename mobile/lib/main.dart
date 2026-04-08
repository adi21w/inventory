import 'package:flutter/material.dart';
import 'pages/auth/login_page.dart';
import 'pages/home/home_page.dart';
import 'pages/pack/pack_page.dart';
import 'pages/pack/pack_form_page.dart';
import 'pages/rack/rack_page.dart';
import 'pages/rack/rack_form_page.dart';
import 'pages/warehouse/warehouse_page.dart';
import 'pages/warehouse/warehouse_form_page.dart';
import 'pages/product/product_page.dart';
import 'pages/product/product_form_page.dart';
import 'pages/stock/stock_page.dart';

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
        '/': (context) => LoginPage(),
        '/home': (context) => const HomePage(),
        '/packs': (context) => const PackPage(),
        '/packs-add': (context) => PackFormPage(),
        '/racks': (context) => const RackPage(),
        '/racks-add': (context) => RackFormPage(),
        '/warehouses': (context) => const WarehousePage(),
        '/warehouses-add': (context) => WarehouseFormPage(),
        '/products': (context) => const ProductPage(),
        '/products-add': (context) => ProductFormPage(),
        '/stocks': (context) => const StockPage(),
      },
    );
  }
}
