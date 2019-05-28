<?php
/**
 * Created by IntelliJ IDEA.
 * User: PC
 * Date: 19/05/2019
 * Time: 16:13
 */

namespace Repo;


class GenericRepo
{
    protected $tabela;

    public function __construct() {

    }
	
	public function listar($colunas, $tabela, $app, $where = null, $order = null) {
        $sql = "SELECT $colunas FROM $tabela ";
        if ($where != null) {
            $sql .= ' ' . $where;
        }
        if ($order != null) {
            $sql .= ' ' . $where;
        }
        $res = array();
        foreach ($app->db->query($sql) as $row) {
            $res[] = $row;
        }
        return $res;
    }

    public function encontrar($colunas, $tabela, $where, $app) {
        $sql = "SELECT $colunas FROM $tabela WHERE $where";

        $app->db->query($sql);
        $res = $app->db->query($sql);

        if ($res->rowCount() >= 0) {
            return $res->fetch();
        } else {
            return false;
        }
    }

    public function atualizar($colunas, $tabela, $where, $app) {
        $query = "";
        foreach ($colunas as $coluna => $valor) {
			if ($valor == null){
				break;
			}else if ($valor == "0" || $valor == "") {
                $query .= "`" . $coluna . "`=NULL,";
            } else {
                $query .= "`" . $coluna . "`='$valor',";
            }
        }
        $query = substr($query, 0, -1);
        $sql = "UPDATE $tabela SET $query WHERE $where";
        $res = $app->db->prepare($sql);
        $res->execute();
        if ($res->rowCount() >= 0) {
            return true;
        } else {
            return false;
        }
    }

    public function inserir($campos, $tabela, $app) {
        $colunas = "";
        $valores = "";
        foreach ($campos as $coluna => $valor) {

            if ($valor == "0" || $valor == "") {
                $colunas .= $coluna . ',';
                $valores .= "NULL,";
            } else {
                $colunas .= $coluna . ',';
                $valores .= "'" . $valor . "',";
            }
        }
        $colunas = substr($colunas, 0, -1);
        $valores = substr($valores, 0, -1);
        $sql = "INSERT INTO $tabela ($colunas) VALUES ($valores)";
        $res = $app->db->prepare($sql);
        $res->execute();
        if ($res->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    /* da delete */
    public function excluir($tabela, $where, $app) {
        $sql = "DELETE FROM $tabela WHERE $where";
        $res = $app->db->prepare($sql);
        $res->execute();
        if ($res->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    /* retorna o ID a ser usado no proximo registro em determinada tabela */
    public function getNextID($tabela) {
        $sql = "SHOW TABLE STATUS LIKE '$tabela'";
        $res = null;
        foreach (self::getInstance()->query($sql) as $row) {
            $res = $row['Auto_increment'];
        }
        return $res;
    }
}