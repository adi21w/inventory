import 'package:flutter/material.dart';
import '../../api/pack_service.dart';

class PackUpdatePage extends StatefulWidget {
  final int packId; // Kita minta ID pas pindah ke halaman ini
  const PackUpdatePage({super.key, required this.packId});

  @override
  State<PackUpdatePage> createState() => _PackUpdatePageState();
}

class _PackUpdatePageState extends State<PackUpdatePage> {
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _apiService = PackService();
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadCurrentData();
  }

  // Ambil data lama dari API View
  void _loadCurrentData() async {
    final data = await _apiService.fetchPackDetail(widget.packId);
    if (data != null) {
      setState(() {
        _nameController.text = data['pack']; // Isi input dengan nama lama
        _isLoading = false;
      });
    }
  }

  void _handleUpdate() async {
    if (_formKey.currentState!.validate()) {
      setState(() => _isLoading = true);
      final result = await _apiService.updatePack(
        widget.packId,
        _nameController.text,
      );
      setState(() => _isLoading = false);

      if (result['success']) {
        if (mounted) {
          ScaffoldMessenger.of(
            context,
          ).showSnackBar(SnackBar(content: Text(result['message'])));
          Navigator.pop(context, true); // Balik ke list & refresh
        }
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Update Pack"),
        backgroundColor: const Color(0xFF56C7CD),
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : Padding(
              padding: const EdgeInsets.all(16.0),
              child: Form(
                key: _formKey,
                child: Column(
                  children: [
                    TextFormField(
                      controller: _nameController,
                      decoration: const InputDecoration(labelText: "Pack"),
                      validator: (v) => v!.isEmpty ? "Require Pack Name" : null,
                    ),
                    const SizedBox(height: 20),
                    ElevatedButton(
                      onPressed: _handleUpdate,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFF56C7CD),
                      ),
                      child: const Text(
                        "UPDATE",
                        style: TextStyle(color: Colors.white),
                      ),
                    ),
                  ],
                ),
              ),
            ),
    );
  }
}
