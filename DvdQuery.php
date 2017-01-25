<?php
namespace Database\Query;
use PDO;

class DvdQuery
{
  private $host = 'itp460.usc.edu';
  private $database_name = 'dvd';
  private $username = 'student';
  private $password = 'ttrojan';
  private static $pdo;

  private $searchTerm = '';
  private $orderBy = '';

  public function __construct()
  {
    if (!self::$pdo) {
      $connection_string = "mysql:host=$this->host;dbname=$this->database_name";
      self::$pdo = new PDO($connection_string, $this->username, $this->password);
    }
  }
  public function titleContains($term)
  {
    $this->searchTerm = $term;
  }

  public function orderByTitle()
  {
    $this->orderBy = 'title';
  }

  public function find()
  {
    $order = $this->orderBy;
    $sql = "
      SELECT title, genres.genre_name as genre, formats.format_name as format, ratings.rating_name as rating
      FROM dvds
      INNER JOIN genres
      ON genre_id = genres.id
      INNER JOIN formats
      ON format_id = formats.id
      INNER JOIN ratings
      ON rating_id = ratings.id
      AND title LIKE :title_value
      ORDER BY $order ASC;
    ";

    $statement = self::$pdo->prepare($sql);
    $like = "%".$this->searchTerm."%";
    $statement->bindParam(':title_value', $like);
    $statement->execute();
    $res = $statement->fetchAll(PDO::FETCH_OBJ);
    return $res;
  }

}
