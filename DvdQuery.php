<?php
namespace Database\Query;
use PDO;

class DvdQuery
{
  private $host = 'itp460.usc.edu';
  private $database_name = 'dvd';
  private $username = 'student';
  private $password = 'ttrojan';
  public static $pdo;

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
    $this->orderBy = 'Title';
  }

  public function find()
  {
    $sql = "
      SELECT title, genres.genre_name as genre, formats.format_name as format, ratings.rating_name as rating, rating_id
      FROM dvds
      INNER JOIN genres
      ON genre_id = genres.id
      INNER JOIN formats
      ON format_id = formats.id
      INNER JOIN ratings
      ON rating_id = ratings.id
      AND title LIKE ?
      ORDER BY ?;
    ";
    $statement = self::$pdo->prepare($sql);
    $like = "%".$this->searchTerm."%";
    $statement->bindParam(1, $like);
    $statement->bindParam(2, $this->orderBy);
    $statement->execute();
    $res = $statement->fetchAll(PDO::FETCH_OBJ);
    return $res;
  }

}
