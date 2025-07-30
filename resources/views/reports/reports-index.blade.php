<x-app-layout>

    <style>
        .btn-check:checked + .btn {
    background-color: #8CB368;
    color: white;
    border-color: #8CB368;
}

.progress-item {
      margin-bottom: 30px;
    }

    .progress-label {
      margin-bottom: 10px;
      font-size: 16px;
    }

    .progress-bar {
      background-color: #e5e5e5;
      border-radius: 10px;
      overflow: hidden;
      height: 10px;
      width: 100%;
    }

    .progress-fill {
      height: 100%;
      background-color: #8cb368;
      width: 20%; /* Customize width for each bar */
    }

    #textshaped {
        
      /* max-width: 1200px; */
      margin: 4 auto;
      background: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
    }
    #barshaped {
      /* max-width: 1200px; */
      margin: 4 auto;
      background: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
    }

    h1 {
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 30px;
      color: #111;
    }

    h2 {
      font-size: 20px;
      font-weight: 600;
      margin-top: 30px;
      margin-bottom: 10px;
      color: #111;
    }

    ul {
      margin-left: 20px;
      margin-top: 10px;
    }

    ul li {
      margin-bottom: 10px;
    }

    strong {
      font-weight: 600;
      color: #333;
    }

    .purchase_btn{
        background-color: #8CB368;
    color: white;
    border-color: #8CB368;
    }
    .purchase_btn:hover{
         background-color: #5d714a;
    color: white;
    border-color: #8CB368;
    }

    </style>
    <div class="p-3 max-w-7xl mx-auto space-y-4">
    

    <div class="col-md-7">
        <div class="d-flex justify-content-between w-100 p-2">
            <button onclick="history.back()" class="fs-4 px-4 py-1 rounded-lg flex items-center space-x-2 hover:bg-red-700 transition hover:text-white">
                <i class="fas fa-arrow-left"></i>
                <span>Back</span>
            </button>
            
            
            <h1 class="text-center fs-4 fw-bold">Reports</h1>
           
            
        </div>


    </div>
    <div class="">
    
    <div class="btn-group" role="group" aria-label="Toggle View">
        <input type="radio" class="btn-check" name="viewToggle" id="barshapped" autocomplete="off">
        <label class="btn btn-outline-secondary rounded-start-pill" for="barshapped">Bar shapped</label>
    
        <input type="radio" class="btn-check" name="viewToggle" id="textshaped-radio" autocomplete="off" checked>
        <label class="btn btn-outline-secondary rounded-end-pill" for="textshaped-radio">Text shaped</label>
    </div>
    <a href="" class="float-end purchase_btn btn hover:purchase_btn">Purchase the Full Report</a>
    </div>





    <div id="barshaped" style="display: none;">
      @foreach ($UserSurveys as $survey)
        @php
            $surveyId = $survey?->id;
            $overallAverage = $surveyId ? ($surveyAverages[$surveyId] ?? 0) : 0;
            $title = $survey?->title ?? 'Untitled';
            $appliesTo = is_array($survey?->applies_to) ? implode(', ', $survey->applies_to) : '';
        @endphp

       @if($surveyId) {{-- Only render if survey exists --}}
          <div class="progress-item">
            <div class="progress-label">
              {{ $title }} {{ $appliesTo ? "($appliesTo)" : '' }}
            </div>
            <div class="progress-bar">
              <div class="progress-fill" style="width: {{ $overallAverage * 20 }}%;"></div>
              
            </div>
            <!-- <div class="mt-2">
                <a href="{{ route('survey.export', ['id' => $surveyId]) }}" class="btn btn-primary">Download Excel</a>
            </div> -->
          </div>
        @endif 
      @endforeach
    </div>
    




    <div id="textshaped" style="display: block;">
        {{-- <h1>Title</h1>

        <h2>Genel Değerlendirme:</h2>
        <p>Bay/Ms. Smith, iş dünyasında deneyimli ve yetenekli bir profesyoneldir. Kendisi 20 yılı aşkın bir süredir çeşitli sektörlerde çalışmıştır ve bu süre zarfında geniş bir bilgi birikimi edinmiştir. Mentorluk becerileriyle tanınan Smith, hem deneyimi hem de kişisel nitelikleriyle takdire şayan biridir.</p>

        <h2>Performans Değerlendirmesi:</h2>
        <ul>
          <li><strong>Bilgi ve Deneyim:</strong> Smith'in iş dünyasındaki bilgi ve deneyimi etkileyicidir. Farklı sektörlerde çalışmış olması, geniş bir perspektif kazanmasını sağlamıştır. Bu deneyimleri, danışanlarına sağladığı rehberlikte büyük bir avantajdır.</li>
          <li><strong>İletişim Becerileri:</strong> İletişimde son derece başarılı olan Smith, danışanlarını anlamak ve onlarla etkili bir şekilde iletişim kurmak konusunda usta biridir. Empatik yaklaşımı, danışanların kendilerini rahat hissetmelerini ve açıkça ifade etmelerini sağlar.</li>
          <li><strong>Mentorluk Yaklaşımı:</strong> Smith'in mentorluk yaklaşımı, danışanlarının ihtiyaçlarına odaklanır ve kişiselleştirilmiş bir rehberlik sunar. Güçlü yönleri belirlerken aynı zamanda gelişim alanlarını da göz önünde bulundurur. Adım adım ilerleyen bir plan sunarak danışanlarının hedeflerine ulaşmasına yardımcı olur.</li>
          <li><strong>Motivasyon ve İlham:</strong> Smith, danışanlarını motive etme konusunda etkili bir rol oynar. Tutkulu ve kararlı tavırları, danışanların kendi hedeflerine odaklanmalarını sağlar. Kendisi, ilham verici bir rol model olarak danışanlarının başarılarını destekler ve teşvik eder.</li>
        </ul>

        <h2>Öneriler ve Gelişim Alanları:</h2>
        <ul>
          <li>Smith’in mentorluk hizmetlerini daha geniş kitlelere ulaştırması için dijital platformları kullanması önerilebilir. Online seminerler veya web tabanlı mentorluk programları gibi uygulamalar, potansiyel danışanlara ulaşmasını sağlayabilir.</li>
          <li>Smith’in profesyonel gelişimine devam etmesi önemlidir. Yenilikleri takip etmek ve sektördeki değişiklikleri anlamak, mentorluk becerilerini güncel tutmasına yardımcı olacaktır.</li>
        </ul>

        <p><strong>Sonuç:</strong> Bay/Ms. Smith, kusursuz mentorluk becerileri ve geniş iş deneyimiyle öne çıkan biridir. Empati, iletişim ve motivasyon konularında son derece başarılı olan Smith, danışanlarının başarılarını artırmak için etkili bir rehberdir. Devam eden destek ve gelişimle, Smith’in mentorluk hizmetlerinin daha da etkili hale geleceğine inanıyoruz.</p> --}}

<table class="table table-bordered text-center" style="border-collapse: collapse; font-size: 12px;">
    <thead>
        <tr>
            <th rowspan="2">Survey names</th>
            <th colspan="4">Personality Analysis</th>
        </tr>
        <tr>
            <th>Overall</th>
            <th>Self-Evaluation</th>
            <th>Family members</th>
            <th>Friends</th>
        </tr>
    </thead>
    <tbody>
        {{-- Individual Self Metrics --}}
        @php
            $selfMetrics = [
                'SELF' => 'Self-esteem',
                'COMPETENCE' => 'Competence',
                'AUTONOMY' => 'Autonomy',
                'RELATEDNESS' => 'Relatedness',
            ];
        @endphp

       @foreach ($selfMetrics as $key => $label)
    @php
        $points = $allreport['points'][$key] ?? 0;
        $ratings = $allreport['ratings'][$key] ?? 0;
            //  dd($points, $ratings);
        // Calculate percentage only if ratings > 0
        $percentage = ($ratings > 0) ? round(($points / ($ratings * 4)) * 100, 2) : 0;

        // Assign quality based on percentage
        $quality = match (true) {
            $percentage >= 84 => ' Perfect',
            $percentage >= 70 => 'Very Good',
            $percentage >= 40 => 'Good',
            default => 'Poor',
        };
    @endphp
    <tr style="background-color: #fffec0;">
        <td>{{ $label }}</td>
        <td>{{ "$percentage% ($quality)" }}</td>
        <td>{{ "$percentage% ($quality)" }}</td>
        <td>-</td>
        <td>-</td>
    </tr>
@endforeach


        {{-- Group Metrics --}}
        @php
            $allKeys = collect([
                ...array_keys($allGroupSurveyResults['family'] ?? []),
                ...array_keys($allGroupSurveyResults['friend'] ?? [])
            ])->unique();

            $getQuality = function ($ratings, $points) {
                if (($points ?? 0) == 0) return [0, 'Poor'];
                $avg = round(($ratings / $points) * 100);
                $quality = $avg >= 84 ? 'Perfect' : ($avg >= 70 ? 'Very Good' : ($avg >= 40 ? 'Good' : 'Poor'));
                return [$avg, $quality];
            };
        @endphp

        @foreach ($allKeys as $trait)
            @php
                // Family result - check if data exists and has valid values
                $familyData = $allGroupSurveyResults['family'][$trait] ?? null;
                [$familyAvg, $familyQuality] = $familyData && 
                    isset($familyData['total_ratings']) && 
                    isset($familyData['total_points']) && 
                    $familyData['total_points'] > 0
                    ? $getQuality($familyData['total_ratings'], $familyData['total_points'])
                    : [null, null];

                // Friend result - check if data exists and has valid values
                $friendData = $allGroupSurveyResults['friend'][$trait] ?? null;
                [$friendAvg, $friendQuality] = $friendData && 
                    isset($friendData['total_ratings']) && 
                    isset($friendData['total_points']) && 
                    $friendData['total_points'] > 0
                    ? $getQuality($friendData['total_ratings'], $friendData['total_points'])
                    : [null, null];

                // Combined average - only include valid averages
                $averages = array_filter([$familyAvg, $friendAvg], fn($v) => is_numeric($v) && $v > 0);
                $combinedAvg = count($averages) ? round(array_sum($averages) / count($averages)) : null;

                $overallQuality = $combinedAvg !== null
                    ? ($combinedAvg >= 84 ? 'Perfect' : ($combinedAvg >= 70 ? ' Very Good' : ($combinedAvg >= 40 ? 'Good' : 'Poor')))
                    : null;
            @endphp

            <tr style="background-color: #d9f2fc;">
                <td>{{ ucfirst(strtolower($trait)) }}</td>
                <td>
                    @if ($combinedAvg !== null)
                        {{ $combinedAvg }} ({{ $overallQuality }})
                    @else
                        -
                    @endif
                </td>
                <td>-</td>
                <td>{{ $familyQuality ? "$familyAvg ($familyQuality)" : '-' }}</td>
                <td>{{ $friendQuality ? "$friendAvg ($friendQuality)" : '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<style>
    table {
        border-collapse: collapse;
        width: 100%;
        font-family: Calibri, sans-serif;
    }

    caption {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    thead th {
        background-color: #c9daf8;
        text-align: center;
        padding: 10px;
        border: 1px solid #999;
    }

    th[colspan="4"] {
        background-color: #c9daf8;
        font-weight: bold;
    }

    tbody td {
        text-align: center;
        padding: 8px;
        border: 1px solid #999;
    }

    td:first-child {
        text-align: left;
        background-color: #d9d9d9;
        font-weight: bold;
    }

    small {
        display: block;
        font-size: 11px;
        color: #444;
        margin-top: 3px;
    }
</style>

@php
    $individual = $surveytypequestion['individual'];
    $family = $surveytypequestion['family'];
    $friend = $surveytypequestion['friend'];
    
    // Collect unique questions based on question_text
    $allQuestions = collect(array_merge(
        $individual['questions'],
        $family['questions'],
        $friend['questions']
    ))->unique('question_text')->values();
@endphp

<table>
    <thead>
        <tr>
            <th rowspan="2">Questions</th>
            <th colspan="4">How I am Perceived</th>
        </tr>
        <tr>
            <th>Overall</th>
            <th>Self-Evaluation</th>
            <th>Family members</th>
            <th>Friends</th>
        </tr>
    </thead>
    <tbody>
        @foreach($allQuestions as $question)
            @php
                $text = $question['question_text'];

                // Get individual (self) data
                $selfQuestion = collect($individual['questions'])->firstWhere('question_text', $text);
                $selfPercentage = null;
                if ($selfQuestion && $selfQuestion['self_total_points'] > 0) {
                    $selfPercentage = round(($selfQuestion['self_total_ratings'] / $selfQuestion['self_total_points']) * 100);
                }

                // Get family data with validation
                $famQuestion = collect($family['questions'])->firstWhere('question_text', $text);
                $famPercentage = null;
                if (
    $famQuestion &&
    isset($famQuestion['others_total_points']) &&
    isset($famQuestion['others_total_ratings']) &&
    isset($famQuestion['self_total_points']) &&
    isset($famQuestion['self_total_ratings'])
) {
    $totalFamilyPoints = $famQuestion['others_total_points'] + $famQuestion['self_total_points'];
    $totalFamilyRatings = $famQuestion['others_total_ratings'] + $famQuestion['self_total_ratings'];

    if ($totalFamilyPoints > 0) {
        $famPercentage = round(($totalFamilyRatings / $totalFamilyPoints) * 100);
    } else {
        $famPercentage = null;
    }
}

                // Get friend data - ONLY use others data (not self data)
                $frndQuestion = collect($friend['questions'])->firstWhere('question_text', $text);
                $frndPercentage = null;
                if (
    $frndQuestion &&
    isset($frndQuestion['others_total_points']) &&
    isset($frndQuestion['others_total_ratings']) &&
    isset($frndQuestion['self_total_points']) &&
    isset($frndQuestion['self_total_ratings'])
) {
    $totalFriendPoints = $frndQuestion['others_total_points'] + $frndQuestion['self_total_points'];
    $totalFriendRatings = $frndQuestion['others_total_ratings'] + $frndQuestion['self_total_ratings'];

    if ($totalFriendPoints > 0) {
        $frndPercentage = round(($totalFriendRatings / $totalFriendPoints) * 100);
    } else {
        $frndPercentage = null;
    }
}


                $getStatus = function($percentage) {
                    if ($percentage === null) return '-';
                    if ($percentage >= 84) return 'Perfect';
                    elseif ($percentage >= 60) return 'Very Good';
                    elseif ($percentage >= 40) return 'Good';
                    elseif ($percentage > 0) return 'Poor';
                    return '-';
                };

                $format = function($percentage) use ($getStatus) {
                    if ($percentage === null) return '-';
                    return $getStatus($percentage) . ', ' . $percentage . '%';
                };

                // Calculate average for overall
                $validPercentages = array_filter([$selfPercentage, $famPercentage, $frndPercentage], function($val) { return $val !== null; });
                $avgPercentage = count($validPercentages) > 0 ? round(array_sum($validPercentages) / count($validPercentages)) : null;
            @endphp

            <tr>
                <td>{{ $text ?: 'Question ' . $question['question_id'] }}</td>
                <td>{{ $avgPercentage ? $getStatus($avgPercentage) . ', ' . $avgPercentage . '%' : '-' }}</td>
                <td>{{ $format($selfPercentage) }}</td>
                <td>{{ $format($famPercentage) }}</td>
                <td>{{ $format($frndPercentage) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- Add this debugging section after line 324 --}}
@php
    // Debug: Let's see the family data structure
    echo "<!-- DEBUG: Family Questions Structure -->";
    foreach ($family['questions'] as $index => $question) {
        echo "<!-- Question $index: " . $question['question_text'] . " -->";
        echo "<!-- Self Points: " . $question['self_total_points'] . ", Self Ratings: " . $question['self_total_ratings'] . " -->";
        echo "<!-- Others Points: " . $question['others_total_points'] . ", Others Ratings: " . $question['others_total_ratings'] . " -->";
    }
@endphp


    </div> 

   <div class="action float-end m-2 d-flex gap-2">
    <!-- Excel download (only for bar view) -->
    <form action="{{ route('survey.export') }}" method="GET" id="excelForm" style="display: none;">
        <input type="hidden" name="type" value="bar">
           @if(Auth::user()->hasRole('admin'))

        <button type="submit" class="btn" style="background-color: #8CB368; color: white;">
            Download Excel
        </button>
        @endif

    </form>
    <!-- Excel download (only for text view) -->
    <form action="{{ route('survey.export') }}" method="GET" id="excelFormText">
        <input type="hidden" name="type" value="text">
        @if(Auth::user()->hasRole('admin'))

        {{-- <button type="submit" class="btn" style="background-color: #8CB368; color: white;">
            Download Excel
        </button> --}}
        @endif
    </form>

    <button class="btn" style="background-color: #8CB368; color: white;">
        Mentor to Share
    </button>
</div>


    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
          const barRadio = document.getElementById('barshapped');
          const textRadio = document.getElementById('textshaped-radio');
          const barDiv = document.getElementById('barshaped');
          const textDiv = document.getElementById('textshaped');
          const excelForm = document.getElementById('excelForm');
          const excelFormText = document.getElementById('excelFormText');
  
          function toggleView() {
              const isBar = barRadio.checked;
  
              barDiv.style.display = isBar ? 'block' : 'none';
              textDiv.style.display = isBar ? 'none' : 'block';
  
              excelForm.style.display = isBar ? 'inline-block' : 'none';
              excelFormText.style.display = isBar ? 'none' : 'inline-block';
          }
  
          toggleView();
          barRadio.addEventListener('change', toggleView);
          textRadio.addEventListener('change', toggleView);
      });
  </script>
  
  
  
      
      
</x-app-layout>



