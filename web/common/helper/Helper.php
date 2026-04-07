<?php

namespace common\helper;

use Yii;

class Helper
{
	public static function callFirstName($name)
	{
		if (strlen($name) > 0) {
			$split = explode(' ', $name);
			return $split[0];
		}

		return 'Guest';
	}

	public static function showRole($uid = null)
	{
		if (is_null($uid)) {
			$uid = Yii::$app->user->identity->id;
		}
		$roles = Yii::$app->authManager->getRolesByUser($uid);
		$show = array_keys($roles)[0];

		return $show;
	}

	public static function get_client_ip()
	{
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if (isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if (isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if (isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

	public static function hitungHari($tanggalAwal, $tanggalAkhir)
	{
		$start = new \DateTime($tanggalAwal);
		$end = new \DateTime($tanggalAkhir);
		return $start->diff($end)->days;
	}

	public static function labelHitungHari($first, $end)
	{
		if ($first < $end) {
			$selisih = self::hitungHari($first, $end);
			if ($selisih < 90) {
				return 'badge bg-danger';
			} else if ($selisih < 150) {
				return 'badge bg-orange';
			} else if ($selisih < 300) {
				return 'badge bg-warning';
			} else if ($selisih < 365) {
				return 'badge bg-success';
			} else {
				return 'badge bg-blue';
			}
		}

		return 'badge bg-dark';
	}

	public static function createSlug($text, $split)
	{
		$text = iconv('UTF-8', 'ASCII//TRANSLIT', $text); // translit
		// $text = strtolower($text);
		$text = preg_replace('/[^a-z0-9\s-]/', '', $text);
		$text = preg_replace('/[\s-]+/', $split, $text);

		return trim($text, $split);
	}

	public static function slugifyUnderscoreClean($text, $parsing)
	{
		$text = preg_replace('/[^a-zA-Z0-9]/', $parsing, $text);
		return preg_replace('/_+/', $parsing, $text); // bersihin double _
	}

	public static function labelForCalendar($sts = 'EVT')
	{
		$list = ['BD' => 'bg-primary', 'HD' => 'bg-danger', 'EVT' => 'bg-blue'];
		return $list[$sts];
	}

	public static function ulangTahunTahunIni($tanggalLahir, $yearCus = null)
	{
		$tahunSekarang = ($yearCus == null) ? date('Y') : $yearCus;
		$bulanHari = date('m-d', strtotime($tanggalLahir)); // ambil bulan & hari
		return $tahunSekarang . '-' . $bulanHari;
	}

	public static function formatTitleCase($text)
	{
		return ucwords(strtolower($text));
	}

	public static function formatTanggalIndo($tanggal)
	{
		$fmt = new \IntlDateFormatter(
			'id_ID',
			\IntlDateFormatter::NONE,
			\IntlDateFormatter::NONE,
			'Asia/Jakarta',
			\IntlDateFormatter::GREGORIAN,
			"dd, MMMM yyyy"
		);
		return $fmt->format(new \DateTime($tanggal));
	}

	public static function diffYear($awal, $akhir)
	{
		$fYear = date('Y', strtotime($awal));
		$lYear = date('Y', strtotime($akhir));
		$diff = false;

		$awal_bulan = date('Y-m-d', strtotime($awal));
		$akhir_bulan = date('Y-m-d', strtotime($akhir));

		if ($fYear != $lYear) {
			$diff = true;
		}

		return ['first' => $awal_bulan, 'last' => $akhir_bulan, 'diff' => $diff, 'year' => [$fYear, $lYear]];
	}

	public static function firstNlastYear($tanggal, $tgt)
	{
		$year = date('Y', strtotime($tanggal));
		if ($tgt == 'f') {
			$result = "{$year}-01-01";
		} else if ($tgt == 'l') {
			$result = "{$year}-12-31";
		} else {
			$result = date('Y-m-d');
		}

		return $result;
	}

	public static function calcPpn($total, $rounding = false)
	{
		$ppn = Yii::$app->params['ppn'];
		$pajak = (float)$total * ($ppn / 100);

		if ($rounding) {
			return ceil($pajak);
		}

		return $pajak;
	}

	public static function totalHargaJual($harga, $margin)
	{
		$bMargin = (float)$harga * ($margin / 100);
		$bHarga = (float)$harga + $bMargin;
		$ppn = self::calcPpn($bHarga);
		return $bHarga + $ppn;
	}

	public static function errorHandling($error)
	{
		$result = implode(', ', array_map(function ($e) {
			return implode(', ', $e);
		}, $error));

		return $result;
	}

	public static function priceSmall($price, $reduce, $rounding = false)
	{
		$total = round($price / $reduce, 2);
		if ($rounding) {
			return ceil($total);
		}

		return $total;
	}
}
