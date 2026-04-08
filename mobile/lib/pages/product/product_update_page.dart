// lib/pages/product/product_update_page.dart
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import '../../api/product_service.dart';
import '../../api/rack_service.dart';
import '../../api/pack_service.dart';
import '../../models/product_model.dart';

class ProductUpdatePage extends StatefulWidget {
  final int productId;
  const ProductUpdatePage({super.key, required this.productId});

  @override
  State<ProductUpdatePage> createState() => _ProductUpdatePageState();
}

class _ProductUpdatePageState extends State<ProductUpdatePage> {
  final _formKey = GlobalKey<FormState>();
  final _apiProduct = ProductService();

  // Controllers
  final _nameController = TextEditingController();
  final _slugController = TextEditingController();
  final _isiBesarController = TextEditingController();
  final _isiKecilController = TextEditingController();
  final _priceController = TextEditingController();
  final _marginController = TextEditingController();

  List<dynamic> _listRak = [];
  List<dynamic> _listKemasan = [];
  int? _selectedRak, _selectedKemasanBesar, _selectedKemasanKecil;

  bool _isLoadingData = true;
  bool _isSubmitting = false;

  @override
  void initState() {
    super.initState();
    _initData();
  }

  Future<void> _initData() async {
    try {
      final results = await Future.wait([
        RackService().fetchRacks(),
        PackService().fetchPacks(),
        _apiProduct.fetchProductDetail(widget.productId),
      ]);

      final List<dynamic> racks = results[0] as List<dynamic>;
      final List<dynamic> packs = results[1] as List<dynamic>;
      final ProductModel? detail = results[2] as ProductModel?;

      if (detail != null) {
        setState(() {
          _listRak = racks;
          _listKemasan = packs;

          _nameController.text = detail.product;
          _slugController.text = detail.slug;
          _isiBesarController.text = detail.isiBesar.toString();
          _isiKecilController.text = detail.isiKecil.toString();
          _priceController.text = detail.price.split('.')[0];
          _marginController.text = detail.margin.split('.')[0];

          _selectedRak = detail.rak.id;
          _selectedKemasanBesar = detail.kemasanBesar.id;
          _selectedKemasanKecil = detail.kemasanKecil.id;

          _isLoadingData = false;
        });
      }
    } catch (e) {
      debugPrint("Error Init Data: $e");
    }
  }

  void _handleUpdate() async {
    if (_formKey.currentState!.validate()) {
      setState(() => _isSubmitting = true);

      // HANYA MENGIRIM FIELD YANG BOLEH DI-UPDATE
      final Map<String, dynamic> formData = {
        "rak": _selectedRak,
        "kemasan_besar": _selectedKemasanBesar,
        "kemasan_kecil": _selectedKemasanKecil,
        "isi_besar": int.tryParse(_isiBesarController.text) ?? 1,
        "isi_kecil": int.tryParse(_isiKecilController.text) ?? 0,
        "price": int.tryParse(_priceController.text) ?? 0,
        "margin": double.tryParse(_marginController.text) ?? 0,
      };

      final result = await _apiProduct.updateProduct(
        widget.productId,
        formData,
      );

      if (mounted) {
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
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text(
          "Update Produk",
          style: TextStyle(color: Colors.white),
        ),
        backgroundColor: const Color(0xFF56C7CD),
        iconTheme: const IconThemeData(color: Colors.white),
      ),
      body: _isLoadingData
          ? const Center(child: CircularProgressIndicator())
          : SingleChildScrollView(
              padding: const EdgeInsets.all(20),
              child: Form(
                key: _formKey,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // --- NAMA & SLUG (READ ONLY) ---
                    _buildLabel("Product Name (Locked)"),
                    TextFormField(
                      controller: _nameController,
                      readOnly: true,
                      decoration: _buildInputDecoration("").copyWith(
                        fillColor: Colors.grey[200],
                        filled: true,
                        suffixIcon: const Icon(Icons.lock_outline, size: 18),
                      ),
                    ),
                    const SizedBox(height: 16),

                    // --- RACK ---
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
                    ),
                    const SizedBox(height: 16),

                    // --- KEMASAN BESAR & KECIL ---
                    _buildLabel("Big Pack Type"),
                    DropdownButtonFormField<int>(
                      value: _selectedKemasanBesar,
                      decoration: _buildInputDecoration("Select Big Pack"),
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
                    ),
                    const SizedBox(height: 16),

                    _buildLabel("Small Pack Type"),
                    DropdownButtonFormField<int>(
                      value: _selectedKemasanKecil,
                      decoration: _buildInputDecoration("Select Small Pack"),
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
                    ),
                    const SizedBox(height: 16),

                    // --- ISI BESAR & KECIL ---
                    Row(
                      children: [
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              _buildLabel("Big Qty"),
                              TextFormField(
                                controller: _isiBesarController,
                                keyboardType: TextInputType.number,
                                maxLength: 4,
                                inputFormatters: [
                                  FilteringTextInputFormatter.digitsOnly,
                                ],
                                decoration: _buildInputDecoration(
                                  "1",
                                ).copyWith(counterText: ""),
                              ),
                            ],
                          ),
                        ),
                        const SizedBox(width: 16),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              _buildLabel("Small Qty"),
                              TextFormField(
                                controller: _isiKecilController,
                                keyboardType: TextInputType.number,
                                maxLength: 4,
                                inputFormatters: [
                                  FilteringTextInputFormatter.digitsOnly,
                                ],
                                decoration: _buildInputDecoration(
                                  "1",
                                ).copyWith(counterText: ""),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 16),

                    // --- HARGA & MARGIN ---
                    _buildLabel("Price"),
                    TextFormField(
                      controller: _priceController,
                      keyboardType: TextInputType.number,
                      inputFormatters: [FilteringTextInputFormatter.digitsOnly],
                      decoration: _buildInputDecoration(
                        "0",
                      ).copyWith(prefixText: "Rp "),
                    ),
                    const SizedBox(height: 16),

                    _buildLabel("Margin (%)"),
                    TextFormField(
                      controller: _marginController,
                      keyboardType: TextInputType.number,
                      maxLength: 3,
                      inputFormatters: [FilteringTextInputFormatter.digitsOnly],
                      decoration: _buildInputDecoration(
                        "0",
                      ).copyWith(suffixText: "%", counterText: ""),
                    ),
                    const SizedBox(height: 32),

                    // --- UPDATE BUTTON ---
                    _isSubmitting
                        ? const Center(child: CircularProgressIndicator())
                        : SizedBox(
                            width: double.infinity,
                            height: 50,
                            child: ElevatedButton(
                              onPressed: _handleUpdate,
                              style: ElevatedButton.styleFrom(
                                backgroundColor: const Color(0xFF56C7CD),
                                shape: RoundedRectangleBorder(
                                  borderRadius: BorderRadius.circular(8),
                                ),
                              ),
                              child: const Text(
                                "UPDATE PRODUCT",
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

  Widget _buildLabel(String text) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8),
      child: Text(
        text,
        style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
      ),
    );
  }

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
