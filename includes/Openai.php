<?php
class Openai{
    private function secret_key(){
        return $secret_key = 'Bearer ******YOUR-KEY-HERE********';
    }

    public function request($engine, $prompt, $max_tokens){ 
        $timestamp = date("c", strtotime("now"));
        $request_body = [
        "prompt" => $prompt,
        "max_tokens" => $max_tokens,
        "temperature" => 0.7,
        "top_p" => 1,
        "presence_penalty" => 0.75,
        "frequency_penalty"=> 0.75,
        "best_of"=> 1,
        "stream" => false,
        ];

        $postfields = json_encode($request_body);
        $curl = curl_init();
        curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.openai.com/v1/engines/" . $engine . "/completions",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $postfields,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: ' . $this->secret_key()
        ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $response="**Beep Boop Does Not Compute** ".$err." | **Bot Task:** ".$prompt." | **Time Stamp:** ".$timestamp;
        } else {
            $response=json_decode($response,true);
            if (isset($response['choices'][0]['text'])) {
                $response=$response['choices'][0]['text'];
            } else if (isset($response['error']['message'])){
                $response="**Beep Boop Does Not Compute** ".$response['error']['message']." | **Bot Task:** ".$prompt." | **Time Stamp:** ".$timestamp;
            } else {
                $response=false;
            }
        
        }

        return $response;

    }

  
}
