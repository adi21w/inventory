var formatRepo = function (repo) {
  if (repo.loading) {
    return repo.text;
  }
  var markup =
    '<div class="row">' +
    '<div class="col-sm-12">' +
    repo.text +
    "</div>" +
    "</div>";
  return '<div style="overflow:hidden;">' + markup + "</div>";
};

var formatRepoTemp = function (repo) {
  if (repo.loading) {
    return repo.text;
  }
  var markup =
    '<div class="row">' +
    '<div class="col-sm-9">' +
    repo.text +
    "</div>" +
    '<div class="col-sm-1">' +
    repo.description +
    "</div>" +
    "</div>";
  return '<div style="overflow:hidden;">' + markup + "</div>";
};

var formatRepoTemp2 = function (repo) {
  if (repo.loading) {
    return repo.text;
  }
  var markup =
    '<div class="row">' +
    '<div class="col-sm-4">' +
    repo.text +
    "</div>" +
    '<div class="col-sm-8">' +
    repo.description +
    "</div>" +
    "</div>";
  return '<div style="overflow:hidden;">' + markup + "</div>";
};

var formatRepoPelanggan = function (repo) {
  if (repo.loading) {
    return repo.text;
  }
  var markup =
    '<div class="row">' +
    '<div class="col-sm-5">' +
    repo.text +
    "</div>" +
    '<div class="col-sm-5">' +
    repo.description +
    "</div>" +
    '<div class="col-sm-5">' +
    repo.memberId +
    "</div>" +
    "</div>";
  return '<div style="overflow:hidden;">' + markup + "</div>";
};

var formatRepoSelectionTemp = function (repo) {
  return repo.text || repo.id;
};

var resultJStemp = function (data, params) {
  params.page = params.page || 1;
  return {
    // Change `data.items` to `data.results`.
    // `results` is the key that you have been selected on
    // `actionJsonlist`.
    results: data.results,
  };
};

var formatSTT = function (params) {
  let gudang = $("#gudang-asal").val();
  if (!gudang) {
    alert("Masukan Gudang Asal Terlebih Dahulu !");
    return false;
  }
  return { q: params.term, page: params.page, gudang };
};

var formatError = function () {
  return "Waiting for results...";
};
var formatData = function (params) {
  return { q: params.term, page: params.page };
};
var formatMarkup = function (markup) {
  return markup;
};
var formatData2 = function (params) {
  return { q: params.term };
};
