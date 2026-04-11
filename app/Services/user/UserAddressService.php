
<?php


//الميثود المجمعه الخاص بعرض العنلوين بتاع اليوزر

//الميثود المجمعه الخاص باضافة عنوان جديد لليوزر

//الميثود المجمعه الخاصة بالايديت بتعديل عنوان معين لليوزر


//الميثود المجمعه الخاصة بالابديت بتعديل عنوان معين لليوزر

//الميثود المجمعه الخاص بحذف عنوان معين لليوزر

//الميثود المجمعه الخاص بتعيين عنوان معين كعنوان افتراضي لليوزر






//الميثودز الصغيرة

//االميثود الي بتجيب الادريسيز بتاع اليوزر بنأن علي الايدي بتاعه ولا مفيش هترجع رساله "No addresses found for this user yet, Add your first address'

//اليمثود الي هتتشيك هل العوان الي اليوزر عاوز يضيفه ديفولت ولو ديفولت تعمل ابديت علي باقي العناوين عناوين بتاع اليوز تخاي الديفولت بفولص


//الميثود الي هتعمل كريت للداتا الي راجعه من الريكويست بعد ما اتعملها فالديشن وتخزن عنوان جديدفي الداتا بيز

//الميثود الي هتاخد ال id بتاع العنوان وترجعه بيانات العنوان لاي اليوزر عاوز يعمله ايديت

//الميثود الي هتتشيك هل العوان الي اليوزر عاوز يعمله ايديت ديفولت ولو ديفولت تعمل ابديت علي باقي العناوين عناوين بتاع اليوز تخاي الديفولت بفولص


//الميثود الي هتعمل ابديت للداتا الي راجعه من الريكويست بعد ما اتعملها فالديشن وتخزن التعديلات في الداتا بيز

//الميثود الي هتاخد ال id بتاع العنوان وترجعه بيانات العنوان لاي اليوزر عاوز يعمله حذف




// --- الميثودز المجمعة (Flows) ---

// ميثود العرض: تنادي getUserAddresses
public function listAddressesFlow(User $user) : array
 { 
    $addresses = $this->getUserAddresses($user);
    return $addresses;


 }

// ميثود الإضافة: تنادي (resetOtherDefaults لو محتاج) + dbStoreAddress
public function storeAddressFlow(User $user, array $data) { 
    DB::transaction(function () use ($user, $data) {
        if ($data['is_default']) {
            $this->resetOtherDefaults($user, $data);
        }
        $this->dbStoreAddress($user, $data);
    });
 }

// ميثود التعديل: تنادي (findUserAddressOrFail) + (resetOtherDefaults لو محتاج) + dbUpdateAddress
public function updateAddressFlow(User $user, $addressId, array $data) { 
    DB::transaction(function () use ($user, $data ,$addressId) {
        if ($data['is_default']) {
            $this->dbUpdateDefaultAddress($user, $data);
        }
        $address= $this->dbUpdateAddress($address, $data);
        return $address;
    });
 }

// ميثود الحذف: تنادي (findUserAddressOrFail) + dbDeleteAddress
public function deleteAddressFlow(User $user, $addressId) {  
    DB::transaction(function () use ($user, $addressId) {
        $address = $this->findUserAddressOrFail($addressId, $user);
        $this->dbDeleteAddress($address);
    });
 }
}







// --- الميثودز الصغيرة (Actions) ---

// 1. تجيب كل عناوين المستخدم
protected function getUserAddresses($user) { 
    $addresses =$user->addresses()->get();
    return $addresses;
 }

// 2. تجيب عنوان واحد بالـ ID وتتأكد إنه موجود (وإنه يخص المستخدم)
protected function findUserAddressOrFail($addressId, $user) { 
    $address =$user->addresses()->find($addressId);
    if (!$address) {
        throw new \Exception('Address not found', 404);
    }
 }

// 3. لو العنوان الجديد ديفولت، تخلي باقي عناوين المستخدم مش ديفولت
protected function resetOtherDefaults($user, $data) { 
            if ($data['is_default']) {
            $user->addresses()->update(['is_default' => false]);
        }
 }

// 4. الحفظ الفعلي (Create)
protected function dbStoreAddress($user, array $data) { 
  
    $user->addresses()->create($data);
 }

// 5. التعديل الفعلي (Update)
protected function dbUpdateAddress($address, array $data) { 
    $address->update($data['validated']);
 }

// 6. الحذف الفعلي (Delete)
protected function dbDeleteAddress($addressId) { 
    $address->where('id', $addressId)->delete();
 }


 // 5. التعديل الديفولت لو الي هيتحذف كلن ديفولت (Update)
protected function dbUpdateDefaultAddress($address, array $data) { 
           if($address->is_default){
            $nextAddress = $user->addresses()->where('id', '!=' , $address->id)->first();
            if($nextAddress){
                $nextAddress->update(['is_default' => true]);
            }
        }
 }




