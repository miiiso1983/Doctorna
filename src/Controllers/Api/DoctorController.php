<?php
namespace App\Controllers\Api;

use App\Core\DB;
use PDO;

class DoctorController extends BaseApiController
{
    // Simple nearby search using latitude/longitude in doctors table
    public function nearby(): void
    {
        $lat = (float)($this->request->get['lat'] ?? 0);
        $lng = (float)($this->request->get['lng'] ?? 0);
        $radiusKm = (float)($this->request->get['radius_km'] ?? 25);
        [$page,$per,$offset] = $this->paginationParams();

        $pdo = DB::conn($this->config);
        $sql = "SELECT d.id, u.name, d.latitude, d.longitude, s.name AS specialization,
                (6371 * acos(cos(radians(:lat)) * cos(radians(d.latitude)) * cos(radians(d.longitude) - radians(:lng)) + sin(radians(:lat)) * sin(radians(d.latitude)))) AS distance_km
                FROM doctors d
                JOIN users u ON u.id = d.user_id
                LEFT JOIN specializations s ON s.id = d.specialization_id
                WHERE d.latitude IS NOT NULL AND d.longitude IS NOT NULL
                HAVING distance_km <= :radius
                ORDER BY distance_km ASC
                LIMIT :per OFFSET :off";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':lat', $lat);
        $stmt->bindValue(':lng', $lng);
        $stmt->bindValue(':radius', $radiusKm);
        $stmt->bindValue(':per', $per, PDO::PARAM_INT);
        $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->ok($rows, ['page'=>$page,'per_page'=>$per,'total'=>null,'count'=>count($rows)]);
    }
}

