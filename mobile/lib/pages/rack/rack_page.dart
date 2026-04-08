// lib/rack_page.dart
import 'package:flutter/material.dart';
import '../../api/rack_service.dart';
import '../layout/main_layout.dart';
import 'rack_update_page.dart';

class RackPage extends StatefulWidget {
  const RackPage({super.key});

  @override
  State<RackPage> createState() => _RackPageState();
}

class _RackPageState extends State<RackPage> {
  final RackService _rackService = RackService();
  List<dynamic> _racks = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadRacks();
  }

  Future<void> _loadRacks() async {
    final data = await _rackService.fetchRacks();
    setState(() {
      _racks = data ?? [];
      _isLoading = false;
    });
  }

  @override
  Widget build(BuildContext context) {
    return MainLayout(
      title: "List Racks",
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
                        '/racks-add',
                      );
                      if (refresh == true) {
                        _loadRacks(); // Refresh otomatis pas balik dari form
                      }
                    },
                    icon: const Icon(Icons.add),
                    label: const Text("Add Rack"),
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
                _racks.isEmpty
                    ? const Center(child: Text("Data kemasan kosong"))
                    : ListView.builder(
                        shrinkWrap: true,
                        physics: const NeverScrollableScrollPhysics(),
                        itemCount: _racks.length,
                        itemBuilder: (context, index) {
                          final item = _racks[index];
                          return Card(
                            margin: const EdgeInsets.symmetric(vertical: 8),
                            child: ListTile(
                              leading: const Icon(
                                Icons.inventory_2,
                                color: Color(0xFF56C7CD),
                              ),
                              title: Text("${item['rack']}"),
                              trailing: const Icon(
                                Icons.arrow_forward_ios,
                                size: 16,
                              ),
                              onTap: () async {
                                // Pindah ke halaman update sambil kirim ID
                                var refresh = await Navigator.push(
                                  context,
                                  MaterialPageRoute(
                                    builder: (context) => RackUpdatePage(
                                      rackId: item['id'],
                                    ), // Ambil ID dari list item
                                  ),
                                );

                                if (refresh == true) {
                                  _loadRacks(); // Refresh list kalau ada perubahan
                                }
                              },
                            ),
                          );
                        },
                      ),
              ],
            ),
    );
  }
}
