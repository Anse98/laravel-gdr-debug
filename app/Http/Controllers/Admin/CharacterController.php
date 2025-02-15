<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Models\Item;
use App\Models\Type;
use Illuminate\Http\Request;

class CharacterController extends Controller
{
    public function index()
    {

        $characters = Character::all()->sortBy('level');
        $types = Type::all();

        return view('characters.index', compact('characters', 'types'));
    }

    public function show(Character $character)
    {
        return view('characters.show', compact('character'));
    }

    public function create()
    {
        $types = Type::all();
        $items = Item::all();

        return view('characters.create', compact('types', 'items'));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $newCharacter = Character::create($data);

        if ($request->has('items')) {
            $newCharacter->items()->attach($data['items']);
        };

        return redirect()->route('characters.show', $newCharacter->id);
    }

    public function edit(Character $character)
    {

        $types = Type::all();
        $items = Item::all();

        return view('characters.edit', compact('character', 'types', 'items'));
    }

    public function update(Request $request, Character $character)
    {
        $data = $request->all();
        $character->update($data);

        if ($request->has('items')) {
            $character->items()->sync($data['items']);
        } else {
            $character->items()->sync([]);
        };

        return redirect()->route('characters.show', $character->id);
    }

    public function destroy(Character $character)
    {

        $character->items()->sync([]);

        $character->delete();

        return redirect()->route('characters.index');
    }
}
