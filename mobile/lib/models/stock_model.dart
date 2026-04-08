class StockDetail {
  final int qtyIn, qtyOut, qtyTotal;
  final String batch, expired;

  StockDetail({
    required this.qtyIn,
    required this.qtyOut,
    required this.qtyTotal,
    required this.batch,
    required this.expired,
  });

  factory StockDetail.fromJson(Map<String, dynamic> json) {
    return StockDetail(
      qtyIn: json['qty_in'],
      qtyOut: json['qty_out'],
      qtyTotal: json['qty_total'],
      batch: json['batch'],
      expired: json['expired'],
    );
  }
}

class StockModel {
  final String product, warehouse, lastUpdatedBy;
  final int qty;
  final List<StockDetail> detail;

  StockModel({
    required this.product,
    required this.warehouse,
    required this.qty,
    required this.detail,
    required this.lastUpdatedBy,
  });

  factory StockModel.fromJson(Map<String, dynamic> json) {
    return StockModel(
      product: json['product'],
      warehouse: json['warehouse'],
      qty: json['qty'],
      lastUpdatedBy: json['last_updated_by'],
      detail: (json['detail'] as List)
          .map((i) => StockDetail.fromJson(i))
          .toList(),
    );
  }
}
