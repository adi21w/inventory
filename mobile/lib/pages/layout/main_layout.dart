// lib/main_layout.dart
import 'package:flutter/material.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import '../auth/login_page.dart';

class MainLayout extends StatelessWidget {
  final Widget content; // Tempat isi halaman (Main Content)
  final String title; // Judul di Header

  const MainLayout({super.key, required this.content, required this.title});

  // Fungsi Logout: Hapus token dan tendang ke halaman login
  Future<void> _logout(BuildContext context) async {
    const storage = FlutterSecureStorage();
    await storage.delete(key: 'jwt_token');

    if (context.mounted) {
      Navigator.pushAndRemoveUntil(
        context,
        MaterialPageRoute(builder: (context) => LoginPage()),
        (route) => false, // Hapus semua riwayat navigasi biar nggak bisa "back"
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      // --- HEADER (AppBar) ---
      appBar: AppBar(
        title: Text(title, style: const TextStyle(color: Colors.white)),
        backgroundColor: const Color(0xFF56C7CD),
        iconTheme: const IconThemeData(
          color: Colors.white,
        ), // Biar ikon drawer putih
        actions: [
          IconButton(
            icon: const Icon(Icons.notifications),
            onPressed: () {
              ScaffoldMessenger.of(context).showSnackBar(
                const SnackBar(content: Text('Notifikasi belum tersedia')),
              );
            },
          ),
          IconButton(
            icon: const Icon(Icons.person),
            onPressed: () {
              ScaffoldMessenger.of(context).showSnackBar(
                const SnackBar(content: Text('Profil belum tersedia')),
              );
            },
          ),
        ],
      ),

      // --- SIDEBAR (Drawer) ---
      drawer: Drawer(
        child: ListView(
          padding: EdgeInsets.zero,
          children: [
            const DrawerHeader(
              decoration: BoxDecoration(color: const Color(0xFF56C7CD)),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisAlignment: MainAxisAlignment.end,
                children: [
                  CircleAvatar(
                    backgroundColor: Colors.white,
                    child: Icon(Icons.person),
                  ),
                  SizedBox(height: 10),
                  Text(
                    'Admin Inventory',
                    style: TextStyle(color: Colors.white, fontSize: 18),
                  ),
                ],
              ),
            ),
            ListTile(
              leading: const Icon(Icons.dashboard),
              title: const Text('Dashboard'),
              onTap: () {
                Navigator.pop(context); // Tutup drawer
                Navigator.pushReplacementNamed(context, '/home');
              },
            ),
            ListTile(
              leading: const Icon(Icons.category),
              title: const Text('Products'),
              onTap: () {
                // <--- PAKAI onTap
                Navigator.pop(context);
                ScaffoldMessenger.of(
                  context,
                ).showSnackBar(const SnackBar(content: Text('Coming Soon')));
              },
            ),
            ListTile(
              leading: const Icon(Icons.label),
              title: const Text('Packs'),
              onTap: () {
                Navigator.pop(context);
                Navigator.pushReplacementNamed(context, '/packs');
              },
            ),
            ListTile(
              leading: const Icon(Icons.chrome_reader_mode),
              title: const Text('Racks'),
              onTap: () {
                // <--- PAKAI onTap
                Navigator.pop(context);
                ScaffoldMessenger.of(
                  context,
                ).showSnackBar(const SnackBar(content: Text('Coming Soon')));
              },
            ),
            ListTile(
              leading: const Icon(Icons.warehouse),
              title: const Text('Warehouses'),
              onTap: () {
                // <--- PAKAI onTap
                Navigator.pop(context);
                ScaffoldMessenger.of(
                  context,
                ).showSnackBar(const SnackBar(content: Text('Coming Soon')));
              },
            ),
            ListTile(
              leading: const Icon(Icons.inventory),
              title: const Text('Stock'),
              onTap: () {
                // <--- PAKAI onTap
                Navigator.pop(context);
                ScaffoldMessenger.of(
                  context,
                ).showSnackBar(const SnackBar(content: Text('Coming Soon')));
              },
            ),
            const Divider(), // Garis pemisah
            ListTile(
              leading: const Icon(Icons.logout, color: Colors.red),
              title: const Text('Logout', style: TextStyle(color: Colors.red)),
              onTap: () {
                // <--- PAKAI onTap
                Navigator.pop(context);
                _logout(context);
              },
            ),
          ],
        ),
      ),

      // --- MAIN CONTENT ---
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(16.0), // Kasih jarak biar nggak mepet
          child: content,
        ),
      ),

      // --- FOOTER (BottomAppBar) ---
      bottomNavigationBar: BottomAppBar(
        shape: const CircularNotchedRectangle(),
        color: const Color(0xFF56C7CD),
        child: SizedBox(
          height: 60,
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceAround,
            children: [
              IconButton(
                icon: const Icon(Icons.home, color: Colors.white),
                onPressed: () {
                  // Tambahkan navigasi ke home jika perlu
                },
              ),
              IconButton(
                icon: const Icon(Icons.inventory, color: Colors.white),
                onPressed: () {},
              ),
              IconButton(
                icon: const Icon(Icons.settings, color: Colors.white),
                onPressed: () {},
              ),
            ],
          ),
        ),
      ),
    );
  }
}
