import 'package:flutter/material.dart';
import '../../api/warehouse_service.dart';

class WarehouseUpdatePage extends StatefulWidget {
  final int warehouseId; // ID yang dikirim dari halaman list
  const WarehouseUpdatePage({super.key, required this.warehouseId});

  @override
  State<WarehouseUpdatePage> createState() => _WarehouseUpdatePageState();
}

class _WarehouseUpdatePageState extends State<WarehouseUpdatePage> {
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _apiService = WarehouseService();

  bool _isLoading = true; // Loading saat ambil data awal
  bool _isSubmitting = false; // Loading saat klik update
  String _selectedStatus = "Ya";

  @override
  void initState() {
    super.initState();
    _loadWarehouseData();
  }

  // Fungsi ambil data lama
  void _loadWarehouseData() async {
    final data = await _apiService.fetchWarehouseDetail(widget.warehouseId);
    if (data != null) {
      setState(() {
        _nameController.text = data['warehouse'] ?? '';
        // Pastiin status dari API sesuai dengan value di Dropdown ("Ya"/"Tidak")
        _selectedStatus = data['stock_status'] ?? "Ya";
        _isLoading = false;
      });
    }
  }

  void _handleUpdate() async {
    if (_formKey.currentState!.validate()) {
      setState(() => _isSubmitting = true);

      final result = await _apiService.updateWarehouse(
        widget.warehouseId,
        _nameController.text,
        _selectedStatus,
      );

      setState(() => _isSubmitting = false);

      if (result['success']) {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text(result['message']),
              backgroundColor: Colors.green,
            ),
          );
          Navigator.pop(context, true); // Balik dan refresh list
        }
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text(
          "Update Warehouse",
          style: TextStyle(color: Colors.white),
        ),
        backgroundColor: const Color(0xFF56C7CD),
        iconTheme: const IconThemeData(color: Colors.white),
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : Padding(
              padding: const EdgeInsets.all(20.0),
              child: Form(
                key: _formKey,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      "Warehouse Name",
                      style: TextStyle(fontWeight: FontWeight.bold),
                    ),
                    const SizedBox(height: 8),
                    TextFormField(
                      controller: _nameController,
                      decoration: const InputDecoration(
                        border: OutlineInputBorder(),
                      ),
                      validator: (v) => v!.isEmpty ? 'Field required' : null,
                    ),
                    const SizedBox(height: 20),
                    const Text(
                      "Stock Status",
                      style: TextStyle(fontWeight: FontWeight.bold),
                    ),
                    const SizedBox(height: 8),
                    DropdownButtonFormField<String>(
                      value: _selectedStatus,
                      decoration: const InputDecoration(
                        border: OutlineInputBorder(),
                      ),
                      items: const [
                        DropdownMenuItem(value: "Ya", child: Text("Ya")),
                        DropdownMenuItem(value: "Tidak", child: Text("Tidak")),
                      ],
                      onChanged: (val) =>
                          setState(() => _selectedStatus = val!),
                    ),
                    const SizedBox(height: 30),
                    _isSubmitting
                        ? const Center(child: CircularProgressIndicator())
                        : SizedBox(
                            width: double.infinity,
                            height: 50,
                            child: ElevatedButton(
                              onPressed: _handleUpdate,
                              style: ElevatedButton.styleFrom(
                                backgroundColor: const Color(0xFF56C7CD),
                              ),
                              child: const Text(
                                "UPDATE DATA",
                                style: TextStyle(color: Colors.white),
                              ),
                            ),
                          ),
                  ],
                ),
              ),
            ),
    );
  }
}
