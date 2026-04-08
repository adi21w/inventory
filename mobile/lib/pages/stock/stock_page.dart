import 'package:flutter/material.dart';
import '../../api/stock_service.dart';
import '../../models/stock_model.dart';

class StockPage extends StatefulWidget {
  const StockPage({super.key});

  @override
  State<StockPage> createState() => _StockPageState();
}

class _StockPageState extends State<StockPage> {
  final StockService _stockService = StockService();
  List<StockModel> _listStock = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadStockData();
  }

  // Fungsi untuk ambil data dari API
  Future<void> _loadStockData() async {
    setState(() => _isLoading = true);
    final data = await _stockService.fetchStocks();
    setState(() {
      _listStock = data;
      _isLoading = false;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text(
          "Stock Inventory",
          style: TextStyle(color: Colors.white),
        ),
        backgroundColor: const Color(0xFF56C7CD),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh, color: Colors.white),
            onPressed: _loadStockData,
          ),
        ],
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _listStock.isEmpty
          ? const Center(child: Text("No stock data available"))
          : RefreshIndicator(
              onRefresh: _loadStockData,
              child: ListView.builder(
                padding: const EdgeInsets.all(12),
                itemCount: _listStock.length,
                itemBuilder: (context, index) {
                  final item = _listStock[index];
                  return _buildStockCard(item);
                },
              ),
            ),
    );
  }

  // Widget Card Produk
  Widget _buildStockCard(StockModel item) {
    return Card(
      elevation: 2,
      margin: const EdgeInsets.only(bottom: 12),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: ExpansionTile(
        leading: CircleAvatar(
          backgroundColor: const Color(0xFF56C7CD).withOpacity(0.1),
          child: const Icon(
            Icons.inventory_2_outlined,
            color: Color(0xFF56C7CD),
          ),
        ),
        title: Text(
          item.product,
          style: const TextStyle(fontWeight: FontWeight.bold),
        ),
        subtitle: Text("Total: ${item.qty} units in ${item.warehouse}"),
        children: [
          const Divider(height: 1),
          // Loop detail batch di sini
          ...item.detail.map(
            (d) => ListTile(
              dense: true,
              title: Text(
                "Batch: ${d.batch}",
                style: const TextStyle(fontWeight: FontWeight.bold),
              ),
              subtitle: Text("Exp: ${d.expired}"),
              trailing: Text(
                "${d.qtyTotal}",
                style: const TextStyle(
                  fontWeight: FontWeight.bold,
                  fontSize: 14,
                  color: Colors.green,
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}
