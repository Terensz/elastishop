<?php
namespace framework\packages\ToolPackage\repository;

use framework\component\parent\DbRepository;
use framework\packages\ToolPackage\entity\TechnicalFile;

class TechnicalFileRepository extends DbRepository
{
    public function __construct()
    {

    }
    protected $id;
    protected $title;
    protected $path;
    protected $name;
    protected $mime;
    protected $originalName;
    protected $extension;
    protected $category;
    protected $createdAt;
    protected $removedAt;
    protected $active;

    // public function find($id)
    // {
    //     $stm = "SELECT id, title, path, name, type, mime, original_name, extension,
    //                 created_at, removed_at, active
    //             FROM file WHERE id = :id ";
    //     $dbm = $this->getDbManager();
    //     $params = [
    //         'id' => $id
    //     ];
    //     return $this->makeObject($dbm->findOne($stm, $params));
    // }

    // public function update($file)
    // {
    //
    // }

    // public function insert($file)
    // {
    //     $stm = "INSERT INTO file (title, path, name, mime, original_name,
    //                     extension, category, created_at, removed_at, active)
    //                 VALUES(:title, :path, :name, :mime, :originalName,
    //                     :extension, :category, now(), :removedAt, true)";
    //     $params = [
    //         'title' => $file->getTitle(),
    //         'path' => $file->getPath(),
    //         'name' => $file->getName(),
    //         'mime' => $file->getMime(),
    //         'originalName' => $file->getOriginalName(),
    //         'extension' => $file->getExtension(),
    //         'category' => $file->getCategory(),
    //         'removedAt' => $file->getRemovedAt()
    //     ];
    //     $id = $this->getDbManager()->execute($stm, $params);
    //     return $id;
    // }

    public function getQueryBase($queryType)
    {
        $select = $queryType == 'result' ? "SELECT a.id as \"id\", a.title as \"title\"
                , a.path as \"path\", a.name as \"name\", a.type as \"type\"
                , a.mime as \"mime\", a.original_name as \"original_name\", a.extension as \"extension\"
                , a.created_at as \"created_at\", a.removed_at as \"removed_at\", a.active as \"active\""
                : "SELECT count(*) as count ";

        return $select."
                FROM file a
                LEFT JOIN user_account_file uaf on uaf.file_id = a.id";
    }

    // public function getFilteredResult($filter = null, $options = null)
    // {
    //     $queryType = isset($options['queryType']) ? $options['queryType'] : 'result';
    //     $page = isset($options['page']) ? $options['page'] : 1;
    //     $limit = isset($options['limit']) ? $options['limit'] : 5;
    //     $pageFirstIndex = (($page - 1) * $limit);
    //     // $filter = array(
    //     //     'searchAccountId'   => $searchAccountId,
    //     //     'searchName'        => $searchName,
    //     //     'searchEmail'       => $searchEmail,
    //     //     'searchUsername'    => $searchUsername
    //     // );
    //     $where = array();
    //     if (isset($filter['type']) && $filter['type'] != '') {
    //         $where[] = "a.type = '".$filter['type']."'";
    //     }
    //     // if (isset($filter['searchName']) && $filter['searchName'] != '') {
    //     //     $where[] = "p.full_name = '".$this->encrypt($filter['searchName'])."'";
    //     // }
    //     // if (isset($filter['searchEmail']) && $filter['searchEmail'] != '') {
    //     //     $where[] = "p.email = '".$this->encrypt($filter['searchEmail'])."'";
    //     // }
    //     // if (isset($filter['searchUsername']) && $filter['searchUsername'] != '') {
    //     //     $where[] = "p.username = '".$this->encrypt($filter['searchUsername'])."'";
    //     // }
    //     $where = $where == array() ? '' : ' where '.implode(' and ',$where);
    //     $stm = $this->getQueryBase($queryType)."
    //             ".$where."
    //             ".($queryType == 'result' ? "LIMIT {$pageFirstIndex}, {$limit}" : "")."
    //     ";

    //     if ($queryType == 'result') {
    //         // dump($stm);exit;
    //     }

    //     $filesRaw = $this->getDbManager()->findAll($stm);

    //     return $queryType == 'result'
    //         ? $this->assembleFiles($filesRaw)
    //         : $filesRaw[0]['count'];
    // }

    public function getTotalCount($filter)
    {
        return $this->getFilteredResult($filter, ['queryType' => 'count']);
    }

    public function makeObject($rawData)
    {
        $this->getContainer()->wireService('ToolPackage/entity/TechnicalFile');
        $file = new TechnicalFile();
        $file->setId(isset($rawData['id']) ? $rawData['id'] : null);
        $file->setTitle(isset($rawData['title']) ? $rawData['title'] : null);
        $file->setPath(isset($rawData['path']) ? $rawData['path'] : null);
        $file->setName(isset($rawData['name']) ? $rawData['name'] : null);
        $file->setMime(isset($rawData['mime']) ? $rawData['mime'] : null);
        $file->setOriginalName(isset($rawData['original_name']) ? $rawData['original_name'] : null);
        $file->setExtension(isset($rawData['extension']) ? $rawData['extension'] : null);
        $file->setCategory(isset($rawData['category']) ? $rawData['category'] : null);
        $file->setMime(isset($rawData['mime']) ? $rawData['mime'] : null);
        $file->setCreatedAt(isset($rawData['created_at']) ? $rawData['created_at'] : null);
        $file->setRemovedAt(isset($rawData['removed_at']) ? $rawData['removed_at'] : null);
        $file->setActive(isset($rawData['active']) ? $rawData['active'] : null);
        return $file;
    }

    public function assembleFiles($filesRaw)
    {
        $files = array();
        for ($i = 0; $i < count($filesRaw); $i++) {
            if (!$filesRaw[$i]['id']) {
                array_splice($filesRaw, $i);
                continue;
            }

            $files[] = array(
                'id' => $filesRaw[$i]['id'],
                'title' => $filesRaw[$i]['title'],
                'path' => $filesRaw[$i]['path'],
                'name' => $filesRaw[$i]['name'],
                'type' => $filesRaw[$i]['type'],
                'mime' => $filesRaw[$i]['mime'],
                'originalName' => $filesRaw[$i]['original_name'],
                'extension' => $filesRaw[$i]['extension'],
                'createdAt' => $filesRaw[$i]['created_at'],
                'removedAt' => $filesRaw[$i]['removed_at'],
                'active' => $filesRaw[$i]['active']
            );
        }

        return $files;
    }
}
