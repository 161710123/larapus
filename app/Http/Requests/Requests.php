use Illuminate\Support\Facades\Auth;
class StoreBookRequest extends Request
{
public function authorize()
{
return Auth::check();
}
}
public function rules()
{
return [
'title' => 'required|unique:books,title',
'author_id' => 'required|exists:authors,id',
'amount' => 'numeric',
'cover' => 'image|max:2048'
];
}
