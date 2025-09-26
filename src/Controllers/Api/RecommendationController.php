<?php
namespace App\Controllers\Api;

use App\Core\Controller;
use App\Core\DB;

class RecommendationController extends Controller
{
    // Very simple rule-based mapping from keywords in symptoms -> specialization id
    public function suggestSpecialization(): void
    {
        $symptoms = strtolower((string)($this->request->post['symptoms'] ?? ''));
        $map = [
            'chest|heart|palpitation' => 'Cardiology',
            'skin|rash|acne|derma' => 'Dermatology',
            'child|kid|pediatric|fever kid' => 'Pediatrics',
            'bone|joint|knee|fracture|orth' => 'Orthopedics',
            'headache|migraine|neuro|seizure' => 'Neurology',
        ];
        $pdo = DB::conn($this->config);
        $specialization = 'General Practitioner';
        foreach ($map as $pattern => $name) {
            if (preg_match('/(' . $pattern . ')/', $symptoms)) {
                $specialization = $name;
                break;
            }
        }
        $stmt = $pdo->prepare('SELECT id, name FROM specializations WHERE name = ? LIMIT 1');
        $stmt->execute([$specialization]);
        $row = $stmt->fetch() ?: ['id' => null, 'name' => $specialization];
        $this->response->json(['suggestion' => $row]);
    }
}

