// lib/warehouse_page.dart
import 'package:flutter/material.dart';
import '../../api/warehouse_service.dart';
import '../layout/main_layout.dart';
import 'warehouse_update_page.dart';

class WarehousePage extends StatefulWidget {
  const WarehousePage({super.key});

  @override
  State<WarehousePage> createState() => _WarehousePageState();
}

class _WarehousePageState extends State<WarehousePage> {
  final WarehouseService _warehouseService = WarehouseService();
  List<dynamic> _warehouses = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadWarehouses();
  }

  Future<void> _loadWarehouses() async {
    final data = await _warehouseService.fetchWarehouses();
    setState(() {
      _warehouses = data ?? [];
      _isLoading = false;
    });
  }

  @override
  Widget build(BuildContext context) {
    return MainLayout(
      title: "List Warehouses",
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
                        '/warehouses-add',
                      );
                      if (refresh == true) {
                        _loadWarehouses(); // Refresh otomatis pas balik dari form
                      }
                    },
                    icon: const Icon(Icons.add),
                    label: const Text("Add Warehouse"),
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
                _warehouses.isEmpty
                    ? const Center(child: Text("Data gudang kosong"))
                    : ListView.builder(
                        shrinkWrap: true,
                        physics: const NeverScrollableScrollPhysics(),
                        itemCount: _warehouses.length,
                        itemBuilder: (context, index) {
                          final item = _warehouses[index];
                          return Card(
                            margin: const EdgeInsets.symmetric(vertical: 8),
                            child: ListTile(
                              leading: const Icon(
                                Icons.inventory_2,
                                color: Color(0xFF56C7CD),
                              ),
                              title: Text("${item['warehouse']}"),
                              trailing: const Icon(
                                Icons.arrow_forward_ios,
                                size: 16,
                              ),
                              onTap: () async {
                                var refresh = await Navigator.push(
                                  context,
                                  MaterialPageRoute(
                                    builder: (context) => WarehouseUpdatePage(
                                      warehouseId: item['id'],
                                    ),
                                  ),
                                );

                                if (refresh == true) {
                                  _loadWarehouses(); // Refresh list kalau ada perubahan
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
