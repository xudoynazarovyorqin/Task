<?php


namespace App\Http\Controllers\Traits;


trait ActivateTrait
{
    protected $status_active = 'active',
        $status_deactive = 'deactive';

    public function activated()
    {
        return $this->status == $this->status_active;
    }

    public function isActivated()
    {
        return $this->activated();
    }

    public function activate()
    {
        $this->status = $this->status_active;
        return $this->update();
    }

    public function deactivate()
    {
        $this->status = $this->status_deactive;
        return $this->update();
    }
    public function scopeActive($query)
    {
        return $query->where('status', $this->status_active);
    }
    public function scopeDeactive($query)
    {
        return $query->where('status', $this->status_deactive);
    }
}
