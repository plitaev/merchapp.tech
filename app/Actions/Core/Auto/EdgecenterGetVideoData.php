<?php

namespace App\Actions\Core\Auto;

use App\Models\Core\MiniAppVideo;

class EdgecenterGetVideoData {

    public function handle() {
        $videos = MiniAppVideo::select('id', 'edgecenter_id')->where('edgecenter_status', '!=', 'ready')->get();
        foreach ($videos as $video) {

            $curl=curl_init();
            curl_setopt($curl,CURLOPT_URL,"https://api.edgecenter.ru/streaming/vod/videos/".$video->edgecenter_id);
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type: application/json','Authorization: APIKey '.env('EDGECENTER_API_KEY')]);
            $result = curl_exec($curl);
            curl_close($curl);

            return $result;

            $Aresult=json_decode($result,true);

            $A=['edgecenter_name' => $Aresult['name'],
                'duration'=>$Aresult['duration'],
                'edgecenter_slug'=>$Aresult['slug'],
                'edgecenter_status'=>$Aresult['status'],
                'edgecenter_screenshot_url'=>$Aresult['screenshot'],
                'edgecenter_hls_url'=>$Aresult['hls_url'],
                'edgecenter_views'=>$Aresult['views']
            ];

            MiniAppVideo::updateOrCreate(['id'=>$video->id],$A);
        }
    }

}
