// lib/pages/product/product_page.dart
import 'package:flutter/material.dart';
import '../../api/product_service.dart';
import '../../models/product_model.dart';
import 'product_update_page.dart';
import '../layout/main_layout.dart';

class ProductPage extends StatefulWidget {
  const ProductPage({super.key});

  @override
  State<ProductPage> createState() => _ProductPageState();
}

class _ProductPageState extends State<ProductPage> {
  final ProductService _productService = ProductService();
  late Future<List<ProductModel>> _productsFuture;

  @override
  void initState() {
    super.initState();
    _productsFuture = _productService.fetchProducts();
  }

  // Fungsi untuk refresh data
  void _loadProducts() {
    setState(() {
      _productsFuture = _productService.fetchProducts();
    });
  }

  @override
  Widget build(BuildContext context) {
    return MainLayout(
      title: "Data Produk",
      content: FutureBuilder<List<ProductModel>>(
        future: _productsFuture,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          } else if (snapshot.hasError) {
            return Center(child: Text("Error: ${snapshot.error}"));
          } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
            return const Center(child: Text("Tidak ada produk"));
          }

          final products = snapshot.data!;

          return Column(
            children: [
              // Tombol Tambah
              Padding(
                padding: const EdgeInsets.all(8.0),
                child: ElevatedButton.icon(
                  onPressed: () async {
                    var refresh = await Navigator.pushNamed(
                      context,
                      '/products-add',
                    );
                    if (refresh == true) {
                      _loadProducts(); // Refresh otomatis pas balik dari form
                    }
                  },
                  icon: const Icon(Icons.add),
                  label: const Text("Add product"),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: const Color(0xFF56C7CD),
                    foregroundColor: Colors.white,
                    minimumSize: const Size(double.infinity, 45),
                  ),
                ),
              ),

              // List Produk
              ListView.builder(
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                itemCount: products.length,
                itemBuilder: (context, index) {
                  final item = products[index];
                  return Card(
                    margin: const EdgeInsets.symmetric(
                      horizontal: 8,
                      vertical: 4,
                    ),
                    child: ListTile(
                      leading: Container(
                        width: 50,
                        height: 50,
                        decoration: BoxDecoration(
                          borderRadius: BorderRadius.circular(8),
                          image: DecorationImage(
                            image: NetworkImage(
                              item.image ?? 'https://via.placeholder.com/50',
                            ),
                            fit: BoxFit.cover,
                          ),
                        ),
                      ),
                      title: Text(
                        item.product,
                        style: const TextStyle(fontWeight: FontWeight.bold),
                      ),
                      subtitle: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            "Rak: ${item.rak.nama} | Besar: ${item.kemasanBesar.nama}",
                          ),
                          Text(
                            "Harga: Rp ${item.price}",
                            style: const TextStyle(
                              color: Colors.green,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                        ],
                      ),
                      trailing: const Icon(Icons.chevron_right),
                      onTap: () async {
                        final refresh = await Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => ProductUpdatePage(
                              productId: item.id,
                            ), // ID dari Model
                          ),
                        );

                        if (refresh == true) {
                          _loadProducts(); // Refresh list setelah update
                        }
                      },
                    ),
                  );
                },
              ),
            ],
          );
        },
      ),
    );
  }
}
