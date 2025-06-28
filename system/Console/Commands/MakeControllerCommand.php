<?php

namespace System\Console\Commands;

use System\Console\Command;

/**
 * Make Controller Command
 * 
 * Creates a new controller
 */
class MakeControllerCommand extends Command
{
    /**
     * Command signature
     * 
     * @var string
     */
    protected $signature = 'make:controller';

    /**
     * Command description
     * 
     * @var string
     */
    protected $description = 'Create a new controller class';

    /**
     * Execute the command
     * 
     * @return mixed
     */
    public function handle()
    {
        // Get controller name from argument or ask for it
        $name = $this->argument(0);
        if (empty($name)) {
            $name = $this->ask('Controller name');

            if (empty($name)) {
                $this->error('Controller name cannot be empty');
                return 1;
            }
        }

        // Convert to proper case and ensure "Controller" suffix
        $name = ucfirst($name);
        if (!str_ends_with($name, 'Controller')) {
            $name .= 'Controller';
        }

        // Determine namespace and path
        $namespace = 'App\\Controllers';
        $directory = BASE_PATH . '/app/controllers';

        // Check if it's an API controller
        $isApi = $this->hasOption('api');
        if ($isApi) {
            $namespace .= '\\Api';
            $directory .= '/Api';
        }

        // Create directory if not exists
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Check if controller already exists
        $filePath = $directory . '/' . $name . '.php';
        if (file_exists($filePath)) {
            if (!$this->confirm("Controller '{$name}' already exists. Overwrite?", false)) {
                $this->line('Operation cancelled.');
                return 0;
            }
        }

        // Generate controller content
        $content = $this->generateController($name, $namespace, $isApi);

        // Save file
        file_put_contents($filePath, $content);

        $this->info("Controller created: {$name}");
        $this->line($filePath);

        return 0;
    }

    /**
     * Generate controller class content
     * 
     * @param string $name
     * @param string $namespace
     * @param bool $isApi
     * @return string
     */
    protected function generateController($name, $namespace, $isApi = false)
    {
        // For API controllers, generate a REST controller template
        if ($isApi) {
            // Get the resource name from controller name (without 'Controller' suffix)
            $resource = lcfirst(str_replace('Controller', '', $name));

            $content = <<<EOT
<?php

namespace {$namespace};

use System\Controller;
use System\Request;
use System\Response;

/**
 * {$name} Class
 * 
 * API Controller for {$resource} resource
 */
class {$name} extends Controller
{
    /**
     * Get all resources
     *
     * @param Request \$request
     * @return Response
     */
    public function index(Request \$request)
    {
        // Fetch all resources
        // \$data = YourModel::all();
        
        return \$this->response->json([
            'success' => true,
            'data' => [],
            'message' => '{$resource} list retrieved successfully'
        ]);
    }
    
    /**
     * Get a specific resource
     *
     * @param Request \$request
     * @param int \$id
     * @return Response
     */
    public function show(Request \$request, \$id)
    {
        // Fetch specific resource by ID
        // \$item = YourModel::find(\$id);
        
        return \$this->response->json([
            'success' => true,
            'data' => [],
            'message' => 'Single {$resource} retrieved successfully'
        ]);
    }
    
    /**
     * Create a new resource
     *
     * @param Request \$request
     * @return Response
     */
    public function store(Request \$request)
    {
        // Validate request
        // \$validated = \$request->validate([
        //     'field' => 'required',
        // ]);
        
        // Create new resource
        // \$item = YourModel::create(\$validated);
        
        return \$this->response->json([
            'success' => true,
            'data' => [],
            'message' => '{$resource} created successfully'
        ], 201);
    }
    
    /**
     * Update an existing resource
     *
     * @param Request \$request
     * @param int \$id
     * @return Response
     */
    public function update(Request \$request, \$id)
    {
        // Validate request
        // \$validated = \$request->validate([
        //     'field' => 'required',
        // ]);
        
        // Update resource
        // \$item = YourModel::find(\$id);
        // \$item->update(\$validated);
        
        return \$this->response->json([
            'success' => true,
            'data' => [],
            'message' => '{$resource} updated successfully'
        ]);
    }
    
    /**
     * Delete a resource
     *
     * @param Request \$request
     * @param int \$id
     * @return Response
     */
    public function destroy(Request \$request, \$id)
    {
        // Delete resource
        // \$item = YourModel::find(\$id);
        // \$item->delete();
        
        return \$this->response->json([
            'success' => true,
            'message' => '{$resource} deleted successfully'
        ]);
    }
}

EOT;
        } else {
            // Regular web controller
            $content = <<<EOT
<?php

namespace {$namespace};

use System\Controller;
use System\Request;
use System\View;

/**
 * {$name} Class
 */
class {$name} extends Controller
{
    /**
     * Display index page
     *
     * @param Request \$request
     * @return View
     */
    public function index(Request \$request)
    {
        return view('controller/index', [
            'title' => '{$name}'
        ]);
    }
    
    /**
     * Display create form
     *
     * @param Request \$request
     * @return View
     */
    public function create(Request \$request)
    {
        return view('controller/create', [
            'title' => 'Create New Item'
        ]);
    }
    
    /**
     * Store a new item
     *
     * @param Request \$request
     * @return mixed
     */
    public function store(Request \$request)
    {
        // Validate and save data
        // \$validated = \$request->validate([
        //     'field' => 'required',
        // ]);
        
        // Redirect with success message
        return redirect('route.name')->with('success', 'Item created successfully');
    }
    
    /**
     * Display edit form
     *
     * @param Request \$request
     * @param int \$id
     * @return View
     */
    public function edit(Request \$request, \$id)
    {
        // Find item
        // \$item = YourModel::find(\$id);
        
        return view('controller/edit', [
            'title' => 'Edit Item',
            'item' => null
        ]);
    }
    
    /**
     * Update an item
     *
     * @param Request \$request
     * @param int \$id
     * @return mixed
     */
    public function update(Request \$request, \$id)
    {
        // Validate and update data
        // \$validated = \$request->validate([
        //     'field' => 'required',
        // ]);
        
        // Redirect with success message
        return redirect('route.name')->with('success', 'Item updated successfully');
    }
    
    /**
     * Delete an item
     *
     * @param Request \$request
     * @param int \$id
     * @return mixed
     */
    public function destroy(Request \$request, \$id)
    {
        // Delete item
        // \$item = YourModel::find(\$id);
        // \$item->delete();
        
        // Redirect with success message
        return redirect('route.name')->with('success', 'Item deleted successfully');
    }
}

EOT;
        }

        return $content;
    }
}
