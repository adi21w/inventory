import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import '../../api/product_service.dart';
import '../../api/rack_service.dart'; // Asumsi nama file service lo
import '../../api/pack_service.dart'; // Asumsi nama file service lo

class ProductFormPage extends StatefulWidget {
  const ProductFormPage({super.key});

  @override
  State<ProductFormPage> createState() => _ProductFormPageState();
}

class _ProductFormPageState extends State<ProductFormPage> {
  final _formKey = GlobalKey<FormState>();
  final _apiProduct = ProductService();

  // Controllers untuk input teks
  final _nameController = TextEditingController();
  final _slugController = TextEditingController();
  final _isiBesarController = TextEditingController(text: "1");
  final _isiKecilController = TextEditingController();
  final _priceController = TextEditingController();
  final _marginController = TextEditingController(text: "15");

  // State untuk data Dropdown
  List<dynamic> _listRak = [];
  List<dynamic> _listKemasan = [];

  // Variabel penampung pilihan (ID)
  int? _selectedRak;
  int? _selectedKemasanBesar;
  int? _selectedKemasanKecil;

  bool _isLoadingData = true;
  bool _isSubmitting = false;

  @override
  void initState() {
    super.initState();
    _initData();
  }

  // Function untuk generate slug sederhana
  String _generateSlug(String text) {
    return text
        .toLowerCase()
        .trim()
        .replaceAll(RegExp(r'[^a-z0-9\s-]'), '') // Hapus karakter spesial
        .replaceAll(RegExp(r'\s+'), '-'); // Ganti spasi jadi dash (-)
  }

  // Load data Rak dan Kemasan sekaligus
  Future<void> _initData() async {
    try {
      // Hit 2 API sekaligus
      final results = await Future.wait([
        RackService().fetchRacks(),
        PackService().fetchPacks(),
      ]);

      setState(() {
        _listRak = results[0] as List<dynamic>;
        _listKemasan = results[1] as List<dynamic>;
        _isLoadingData = false;
      });
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Gagal mengambil data master")),
      );
    }
  }

  void _submitData() async {
    if (_formKey.currentState!.validate()) {
      setState(() => _isSubmitting = true);

      // Susun Payload sesuai kebutuhan API Yii2 lo
      final Map<String, dynamic> formData = {
        "product": _nameController.text,
        "slug": _nameController.text.toLowerCase().replaceAll(' ', '-'),
        "status": 1,
        "rak": _selectedRak,
        "kemasan_besar": _selectedKemasanBesar,
        "kemasan_kecil": _selectedKemasanKecil,
        "isi_besar": int.parse(_isiBesarController.text),
        "isi_kecil": int.parse(_isiKecilController.text),
        "price": int.parse(_priceController.text),
        "margin": int.parse(_marginController.text),
      };

      final result = await _apiProduct.createProduct(formData);
      setState(() => _isSubmitting = false);

      if (result['success']) {
        Navigator.pop(context, true);
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(result['message']),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text(
          "Tambah Produk Baru",
          style: TextStyle(color: Colors.white),
        ),
        backgroundColor: const Color(0xFF56C7CD),
        iconTheme: const IconThemeData(color: Colors.white),
      ),
      body: _isLoadingData
          ? const Center(child: CircularProgressIndicator())
          : SingleChildScrollView(
              padding: const EdgeInsets.all(20.0),
              child: Form(
                key: _formKey,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // --- PRODUCT NAME ---
                    _buildLabel("Product Name"),
                    TextFormField(
                      controller: _nameController,
                      decoration: _buildInputDecoration("Example: Vaccum Pump"),
                      onChanged: (value) {
                        // Setiap kali user ngetik, slug otomatis ke-generate di controller-nya
                        setState(() {
                          _slugController.text = _generateSlug(value);
                        });
                      },
                      validator: (v) =>
                          v!.isEmpty ? "Require Product Name" : null,
                    ),
                    const SizedBox(height: 16),

                    // --- RACK DROPDOWN ---
                    _buildLabel("Rack"),
                    DropdownButtonFormField<int>(
                      value: _selectedRak,
                      decoration: _buildInputDecoration("Select Rack"),
                      items: _listRak
                          .map(
                            (e) => DropdownMenuItem<int>(
                              value: e['id'],
                              child: Text(e['rack']),
                            ),
                          )
                          .toList(),
                      onChanged: (val) => setState(() => _selectedRak = val),
                      validator: (v) => v == null ? "Require Rack" : null,
                    ),
                    const SizedBox(height: 16),

                    // --- KEMASAN BESAR ---
                    _buildLabel("Big Pack"),
                    DropdownButtonFormField<int>(
                      value: _selectedKemasanBesar,
                      decoration: _buildInputDecoration("Ex: BOX, KARDUS"),
                      items: _listKemasan
                          .map(
                            (e) => DropdownMenuItem<int>(
                              value: e['id'],
                              child: Text(e['pack']),
                            ),
                          )
                          .toList(),
                      onChanged: (val) =>
                          setState(() => _selectedKemasanBesar = val),
                      validator: (v) => v == null ? "Require Big Pack" : null,
                    ),
                    const SizedBox(height: 16),

                    // --- ISI BESAR ---
                    _buildLabel("Big Pack Quantity"),
                    TextFormField(
                      controller: _isiBesarController,
                      keyboardType: TextInputType.number,
                      decoration: _buildInputDecoration("Example: 10"),
                      maxLength: 4,
                      inputFormatters: [FilteringTextInputFormatter.digitsOnly],
                      validator: (v) => v!.isEmpty ? "Require Quantity" : null,
                    ),
                    const SizedBox(height: 16),

                    // --- KEMASAN KECIL ---
                    _buildLabel("Small Pack"),
                    DropdownButtonFormField<int>(
                      value: _selectedKemasanKecil,
                      decoration: _buildInputDecoration("Ex: PCS, BTL"),
                      items: _listKemasan
                          .map(
                            (e) => DropdownMenuItem<int>(
                              value: e['id'],
                              child: Text(e['pack']),
                            ),
                          )
                          .toList(),
                      onChanged: (val) =>
                          setState(() => _selectedKemasanKecil = val),
                      validator: (v) => v == null ? "Require Small Pack" : null,
                    ),
                    const SizedBox(height: 16),

                    // --- ISI KECIL ---
                    _buildLabel("Small Pack Quantity (per Big Pack)"),
                    TextFormField(
                      controller: _isiKecilController,
                      keyboardType: TextInputType.number,
                      maxLength: 4,
                      inputFormatters: [FilteringTextInputFormatter.digitsOnly],
                      decoration: _buildInputDecoration("Example: 10"),
                      validator: (v) => v!.isEmpty ? "Require Quantity" : null,
                    ),
                    const SizedBox(height: 16),

                    // --- HARGA ---
                    _buildLabel("Price"),
                    TextFormField(
                      controller: _priceController,
                      keyboardType: TextInputType.number,
                      maxLength: 11,
                      inputFormatters: [FilteringTextInputFormatter.digitsOnly],
                      decoration: _buildInputDecoration(
                        "0",
                      ).copyWith(prefixText: "Rp "),
                      validator: (v) => v!.isEmpty ? "Require Price" : null,
                    ),
                    const SizedBox(height: 32),

                    // --- MARGIN ---
                    _buildLabel("Margin"),
                    TextFormField(
                      controller: _marginController,
                      keyboardType: TextInputType.number,
                      maxLength: 4,
                      inputFormatters: [FilteringTextInputFormatter.digitsOnly],
                      decoration: _buildInputDecoration(
                        "0",
                      ).copyWith(suffixText: " %"),
                      validator: (v) => v!.isEmpty ? "Require Margin" : null,
                    ),
                    const SizedBox(height: 32),

                    // --- SAVE BUTTON ---
                    _isSubmitting
                        ? const Center(child: CircularProgressIndicator())
                        : SizedBox(
                            width: double.infinity,
                            height: 50,
                            child: ElevatedButton(
                              onPressed: _submitData,
                              style: ElevatedButton.styleFrom(
                                backgroundColor: const Color(0xFF56C7CD),
                                shape: RoundedRectangleBorder(
                                  borderRadius: BorderRadius.circular(8),
                                ),
                              ),
                              child: const Text(
                                "SAVE PRODUCT",
                                style: TextStyle(
                                  color: Colors.white,
                                  fontWeight: FontWeight.bold,
                                  fontSize: 16,
                                ),
                              ),
                            ),
                          ),
                  ],
                ),
              ),
            ),
    );
  }

  // Helper Widget untuk Label agar kode build lebih bersih
  Widget _buildLabel(String text) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8),
      child: Text(
        text,
        style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
      ),
    );
  }

  // Helper untuk Decoration agar seragam dengan halaman Kemasan/Warehouse
  InputDecoration _buildInputDecoration(String hint) {
    return InputDecoration(
      hintText: hint,
      border: OutlineInputBorder(borderRadius: BorderRadius.circular(8)),
      focusedBorder: OutlineInputBorder(
        borderSide: const BorderSide(color: Color(0xFF56C7CD), width: 2),
        borderRadius: BorderRadius.circular(8),
      ),
      contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 12),
    );
  }
}
