<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\StoreMemberRequest;
use App\Http\Requests\Member\UpdateMemberRequest;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status');

        $members = Member::query()
            ->when($status->isNotEmpty(), fn ($query) => $query->where('status', $status->toString()))
            ->orderByDesc('created_at')
            ->paginate();

        return response()->json($members);
    }

    public function store(StoreMemberRequest $request)
    {
        $member = Member::create($request->validated());

        return response()->json($member, Response::HTTP_CREATED);
    }

    public function show(Member $member)
    {
        return response()->json($member);
    }

    public function update(UpdateMemberRequest $request, Member $member)
    {
        $member->update($request->validated());

        return response()->json($member);
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return response()->noContent();
    }
}
