<?php

require_once 'conn.php';

class Iscrizioni
{
    public static function addIscrizione($idCorso)
    {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO iscrizioni (userId,corsoId) VALUES (:userId,:corsoId)");
        $stmt->bindParam(":corsoId", $idCorso, PDO::PARAM_INT);
        $stmt->bindParam(":userId", $_SESSION["user_id"], PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function deleteIscrizione($idCorso)
    {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM iscrizioni WHERE corsoId=:corsoId && userId=:userId");
        $stmt->bindParam("corsoId", $idCorso, PDO::PARAM_INT);
        $stmt->bindParam("userId", $_SESSION["user_id"], PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function isFull($idCorso)
    {
        global $conn;
        $findIscrizioni = $conn->prepare("SELECT COUNT(*) As nIscrizioni FROM iscrizioni WHERE corsoId=:corsoId");
        $findIscrizioni->bindParam(":corsoId", $idCorso, PDO::PARAM_INT);
        $findIscrizioni->execute();
        $nIscrizioni = $findIscrizioni->fetch(PDO::FETCH_ASSOC);
        $findMaxPartecipanti = $conn->prepare("SELECT maxPartecipanti FROM corsi WHERE id=:id");
        $findMaxPartecipanti->bindParam(":id", $idCorso, PDO::PARAM_INT);
        $findMaxPartecipanti->execute();
        $maxPartecipanti = $findMaxPartecipanti->fetch(PDO::FETCH_ASSOC);
        if ($nIscrizioni["nIscrizioni"] < $maxPartecipanti["maxPartecipanti"]) {
            return self::addIscrizione($idCorso);
        }
        return false;
    }
}
