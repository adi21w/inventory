// lib/pack_page.dart
import 'package:flutter/material.dart';
import '../../api/pack_service.dart';
import '../layout/main_layout.dart';

class PackPage extends StatefulWidget {
  const PackPage({super.key});

  @override
  State<PackPage> createState() => _PackPageState();
}

class _PackPageState extends State<PackPage> {
  final PackService _packService = PackService();
  List<dynamic> _packs = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadPacks();
  }

  Future<void> _loadPacks() async {
    final data = await _packService.fetchPacks();
    setState(() {
      _packs = data ?? [];
      _isLoading = false;
    });
  }

  @override
  Widget build(BuildContext context) {
    return MainLayout(
      title: "List Packs",
      content: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : Column(
              // <--- Bungkus pakai Column
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // 1. TARO TOMBOLNYA DI SINI
                SizedBox(
                  width: double.infinity, // Biar tombolnya lebar (opsional)
                  child: ElevatedButton.icon(
                    onPressed: () async {
                      var refresh = await Navigator.pushNamed(
                        context,
                        '/packs-add',
                      );
                      if (refresh == true) {
                        _loadPacks(); // Refresh otomatis pas balik dari form
                      }
                    },
                    icon: const Icon(Icons.add),
                    label: const Text("Add Pack"),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: const Color(0xFF56C7CD),
                      foregroundColor: Colors.white,
                      padding: const EdgeInsets.symmetric(vertical: 12),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(8),
                      ),
                    ),
                  ),
                ),

                const SizedBox(height: 16), // Jarak antara tombol dan list
                const Divider(), // Garis pemisah biar rapi
                // 2. LIST DATA
                _packs.isEmpty
                    ? const Center(child: Text("Data kemasan kosong"))
                    : ListView.builder(
                        shrinkWrap: true,
                        physics: const NeverScrollableScrollPhysics(),
                        itemCount: _packs.length,
                        itemBuilder: (context, index) {
                          final item = _packs[index];
                          return Card(
                            margin: const EdgeInsets.symmetric(vertical: 8),
                            child: ListTile(
                              leading: const Icon(
                                Icons.inventory_2,
                                color: Color(0xFF56C7CD),
                              ),
                              title: Text("${item['pack']}"),
                              trailing: const Icon(
                                Icons.arrow_forward_ios,
                                size: 16,
                              ),
                            ),
                          );
                        },
                      ),
              ],
            ),
    );
  }
}
