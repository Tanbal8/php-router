
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

        foreach ($data as $index => $item) {
            if ($item['id'] == $id) {
                $removedItem = $item;
                unset($data[$index]);

                if (!$this->save(array_values($data))) {
                    return false;
                }

                return $removedItem;
            }
        }

        return false;
    }

    public function add($data) {
        $items = $this->load();
        $maxId = 0;
        foreach ($items as $item) {
            $maxId = max($maxId, $item['id']);
        }
        $data['id'] = $maxId + 1;
        $items[] = $data;

        if (!$this->save($items)) {
            return false;
        }

        return $data;
    }

    public function edit($id, $newData) {
        $items = $this->load();
        foreach ($items as $index => $item) {
            if ($item['id'] == $id) {
                $newData['id'] = $id;
                $items[$index] = array_merge($item, $newData);

                if (!$this->save($items)) {
                    return false;
                }

                return $items[$index];
            }
        }

        return false;
    }
}
