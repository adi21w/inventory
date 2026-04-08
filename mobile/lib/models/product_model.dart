class ProductModel {
  final int id;
  final String product;
  final String slug;
  final int status;
  final Rak rak;
  final Kemasan kemasanBesar;
  final Kemasan kemasanKecil;
  final int isiBesar;
  final int isiKecil;
  final String price;
  final String margin;
  final String? image;

  ProductModel({
    required this.id,
    required this.product,
    required this.slug,
    required this.status,
    required this.rak,
    required this.kemasanBesar,
    required this.kemasanKecil,
    required this.isiBesar,
    required this.isiKecil,
    required this.price,
    required this.margin,
    this.image,
  });

  // Fungsi "Factory" ini pengganti interface untuk mapping JSON
  factory ProductModel.fromJson(Map<String, dynamic> json) {
    return ProductModel(
      id: json['id'],
      product: json['product'] ?? '',
      slug: json['slug'] ?? '',
      status: json['status'] ?? 0,
      rak: Rak.fromJson(json['rak'] ?? {}),
      kemasanBesar: Kemasan.fromJson(json['kemasan_besar'] ?? {}),
      kemasanKecil: Kemasan.fromJson(json['kemasan_kecil'] ?? {}),
      isiBesar: json['isi_besar'] ?? 0,
      isiKecil: json['isi_kecil'] ?? 0,
      price: json['price']?.toString() ?? '0',
      margin: json['margin']?.toString() ?? '0',
      image: json['image'],
    );
  }
}

class Rak {
  final int id;
  final String nama;

  Rak({required this.id, required this.nama});

  factory Rak.fromJson(Map<String, dynamic> json) {
    return Rak(id: json['id'] ?? 0, nama: json['nama'] ?? '-');
  }
}

class Kemasan {
  final int id;
  final String nama;

  Kemasan({required this.id, required this.nama});

  factory Kemasan.fromJson(Map<String, dynamic> json) {
    return Kemasan(id: json['id'] ?? 0, nama: json['nama'] ?? '-');
  }
}
