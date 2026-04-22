<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Module;
use App\Models\Chatroom;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ChatroomAnalyticsController extends Controller
{
    public function index(Module $module) {
        Gate::authorize('accessAnalytics', $module);

        $chatroom = $module->chatroom;
        $messages = Message::where('chatroom_id', $chatroom->id)->get();

        // get the messages sent per 10 minutes to plot a line chart, map these to the number of messages here
        $messagesPerTenMinutes = $messages->groupBy(fn($message) => $message->created_at->copy()
                                    ->floorMinutes(10)
                                    ->format('H:i'))
                                    ->map(fn($timeGroup)=>$timeGroup->count())
                                    ->sortKeys();

        $wordFrequency = $this->getWordsFrequency($messages);
        $totalMessages = $messages->count();
        $totalUniqueSenders = $messages->pluck('user_id')->unique()->count();
        $totalModuleMembers = $module->users()->count();
        $percentageParticipation = $totalModuleMembers > 0 ? round(($totalUniqueSenders / $totalModuleMembers) * 100, 1) : 0;

        return view('chatroom.analytics', [
            'module'=>$module,
            'messagesPer10Mins'=>$messagesPerTenMinutes,
            'wordFrequency'=>$wordFrequency,
            'totalMessages'=>$totalMessages,
            'totalUniqueSenders'=>$totalUniqueSenders,
            'totalModuleMembers'=>$totalModuleMembers,
            'percentageParticipation'=>$percentageParticipation,
        ]);
    }
    private function getWordsFrequency($messages) {
        $wordsToIgnore = [ // common words sent
                           'hi','hello','hey','hii','hiya','greetings','hola','thanks','thank','you','ok','okay','yes',
                           'ye','yeah','yep','no','nope','please','sorry','bye','goodbye','nice','good','the','an','a','and',
                           'or','but','what','when','at','on','it','its','they','them','to','from','any','can','see','be',"don't"];


        $words[] = [];

        foreach($messages as $message) {
            // 1 returns the words found in the message not the count
            $wordsInMessage = str_word_count(Str::lower($message->body), 1);
            foreach($wordsInMessage as $word) {
                // words less than 3 will be useless anyway
                if(strlen($word) < 3) {
                    continue;
                }
                // if this is one of the words not wanted get rid of it
                if(in_array($word, $wordsToIgnore)) {
                    continue;
                }

                $words[$word] = ($words[$word] ?? 0) + 1;
            }
        }

        // sort words
        arsort($words);

        // take the top 30 words from a session
        return array_slice($words,0,30);
    }
}
