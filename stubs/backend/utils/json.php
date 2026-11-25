<?php 

class JSON {
    private $filePath;

    public function __construct($filePath) {
        $this->filePath = $filePath;
    }

    function exists() {
        return file_exists($this->filePath);
    }

    public function load() {
        if (!$this->exists()) {
            return [];
        }
        $content = file_get_contents($this->filePath);
        if (trim($content) === '') {
            return [];
        }
        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON Error: ' . json_last_error_msg());
        }
        return is_array($data) ? $data : [];
    }

    public function save($data) {
        return file_put_contents(
            $this->filePath,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        ) !== false;
    }

    public function remove($id) {
        $data = $this->load();

        $filteredData = array_filter($data, function ($item) use ($id) {
            return $item['id'] != $id;
        });

        if (count($data) === count($filteredData)) {
            return false;
        }

        return $this->save(array_values($filteredData));
    }
}
