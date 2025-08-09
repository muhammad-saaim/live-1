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

<style>
  /* Responsive wrapper to allow horizontal scroll on small screens */
  .table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    margin-bottom: 1.5rem;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed; /* Equal column widths */
    font-family: Calibri, sans-serif;
  }

  thead th, tbody td {
    border: 1px solid #999;
    padding: 8px;
    text-align: center;
    width: 20%; /* 5 columns = 20% width each */
    word-wrap: break-word;
  }

  /* Left align first column and style background */
  thead th:first-child,
  tbody td:first-child {
    text-align: left;
    background-color: #d9d9d9;
    font-weight: bold;
  }

  thead th {
    background-color: #c9daf8;
    font-weight: bold;
    padding: 10px;
  }

  /* Heading row spanning all columns */
  thead tr:first-child th[colspan="5"] {
    width: 100%;
    font-size: 24px;
    text-align: center;
    padding: 12px 0;
  }

  /* Highlight important traits */
  .highlight {
    background-color: #fffec0;
  }

  /* Responsive font and padding for small screens */
  @media (max-width: 600px) {
    thead th, tbody td {
      font-size: 14px;
      padding: 6px;
    }
  }

  small {
    display: block;
    font-size: 11px;
    color: #444;
    margin-top: 3px;
  }
</style>

{{-- Personality Analysis Table --}}
<div class="table-responsive">
  <table class="table table-bordered table-striped table-hover align-middle mb-4 shadow-sm rounded-3 perception-table">
    <thead class='table-primary'>
      <tr >
        <th style="background-color: var(--bs-table-bg);"  colspan="5">Personality Analysis</th>
      </tr>
      <tr>
        <th style='background-color: var(--bs-table-bg);' rowspan="2">Survey names</th>
        <th rowspan="2">Overall %</th>
        <th rowspan="2">Self-Evaluation %</th>
        <th>Family members %</th>
        <th>Friends % (5 friends should evaluate)</th>
      </tr>
     
    </thead>
    <tbody>
      @php
          $traits = [
              'SELF' => 'Self-esteem',
              'COMPETENCE' => 'Competence',
              'AUTONOMY' => 'Autonomy',
              'RELATEDNESS' => 'Relatedness',
              'SELF-PERCEPTION' => 'Self-perception',
              'RELATIONSHIP' => 'Relationship',
              'INTROVERTS' => 'Introvert',
              'EXTRAVERT' => 'Extravert',
              'ACADEMIC' => 'Academic',
              'SOCIAL' => 'Social',
          ];

          if (!function_exists('formatPercent')) {
              function formatPercent($points, $ratings, $maxPoint ) {
                  return ($ratings > 0) ? round(($points / ($ratings * $maxPoint)) * 100, 0) : null;
              }
          }

          if (!function_exists('quality')) {
              function quality($percent) {
                  return match (true) {
                      $percent >= 84 => 'Perfect',
                      $percent >= 70 => 'Very good',
                      $percent >= 40 => 'Good',
                      default => 'Poor',
                  };
              }
          }
// dd($allreport);

          $familyMap = collect($allGroupSurveyResults['family'] ?? [])->mapWithKeys(fn($v, $k) => [strtolower($k) => $v]);
          $friendMap = collect($allGroupSurveyResults['friend'] ?? [])->mapWithKeys(fn($v, $k) => [strtolower($k) => $v]);
      @endphp

      @foreach ($traits as $key => $label)
          @php
              $lowerKey = strtolower($key);
        $maxPoint = $allreport['by_survey']['max_point_per_question'] ?? 5;
              
              $selfPoints = $allreport['total_points'][$key] ?? 0;
             
              $selfRatings = $allreport['total_ratings'][$key] ?? 0;
              $selfPercent = formatPercent($selfPoints, $selfRatings, $maxPoint);
              $selfText = $selfPercent !== null ? quality($selfPercent) . ', ' . $selfPercent : ' ';

              $famData = $familyMap[$lowerKey] ?? null;
              $famPercent = $famData ? formatPercent($famData['total_points'], $famData['total_ratings'], $maxPoint) : null;
              $famText = $famPercent !== null ? quality($famPercent) . ', ' . $famPercent : ' ';

              $friendData = $friendMap[$lowerKey] ?? null;
              $friendPercent = $friendData ? formatPercent($friendData['total_points'], $friendData['total_ratings'], $maxPoint) : null;
              $friendText = $friendPercent !== null ? quality($friendPercent) . ', ' . $friendPercent : ' ';

              $totalPoints = $selfPoints + ($famData['total_points'] ?? 0) + ($friendData['total_points'] ?? 0);
              $totalRatings = $selfRatings + ($famData['total_ratings'] ?? 0) + ($friendData['total_ratings'] ?? 0);
              $overallPercent = formatPercent($totalPoints, $totalRatings, $maxPoint);
              $overallText = $overallPercent !== null ? quality($overallPercent) . ', ' . $overallPercent : ' ';

              $highlightClass = in_array($key, ['SELF', 'COMPETENCE', 'AUTONOMY', 'RELATEDNESS']) ? 'highlight' : '';
          @endphp

          <tr class="{{ $highlightClass }}">
              <td>{{ $label }}</td>
              <td>{{ $overallText }}</td>
              <td>{{ $selfText }}</td>
              <td>{{ $famText }}</td>
              @if ($friendData && ($friendData['total_ratings'] ?? 0) >= 5)
                  <td>{{ $friendText }}</td>
              @else
                  <td></td>
              @endif
          </tr>
      @endforeach
    </tbody>
  </table>
</div>



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

{{-- How I am Perceived Table --}}
<div class="table-responsive">
   <table class="table table-bordered table-striped table-hover align-middle mb-4 shadow-sm rounded-3 perception-table">
    <thead class='table-primary'>
      <tr>
        <th style='background-color: var(--bs-table-bg);' colspan="5">How I am Perceived</th>
      </tr>
      <tr>
        <th style='background-color: var(--bs-table-bg);'>Questions</th>
        <th>Overall %</th>
        <th>Self-Evaluation %</th>
        <th>Family members %</th>
        <th>Friends %</th>
      </tr>
    </thead>
    <tbody>
      @foreach($allQuestions as $question)
        @php
            $text = $question['question_text'];
            $selfQuestion = collect($individual['questions'])->firstWhere('question_text', $text);
            $selfPercentage = null;
            if ($selfQuestion && $selfQuestion['self_total_points'] > 0) {
                $selfPercentage = round(($selfQuestion['self_total_points'] / ($selfQuestion['self_total_ratings']*5)) * 100);
            }

            $famQuestion = collect($family['questions'])->firstWhere('question_text', $text);
            $famPercentage = null;
            $totalFamilyPoints = 0;
            $totalFamilyRatings = 0;
            if ($famQuestion) {
                $totalFamilyPoints = ($famQuestion['others_total_points'] ?? 0) + ($famQuestion['self_total_points'] ?? 0);
                $totalFamilyRatings = ($famQuestion['others_total_ratings'] ?? 0) + ($famQuestion['self_total_ratings'] ?? 0);
                if ($totalFamilyRatings > 0) {
                    $famPercentage = round(($totalFamilyPoints / ($totalFamilyRatings * 5)) * 100);
                }
            }

            $frndQuestion = collect($friend['questions'])->firstWhere('question_text', $text);
            $frndPercentage = null;
            $totalFriendPoints = 0;
            $totalFriendRatings = 0;
            if ($frndQuestion) {
                $totalFriendPoints = ($frndQuestion['others_total_points'] ?? 0) + ($frndQuestion['self_total_points'] ?? 0);
                $totalFriendRatings = ($frndQuestion['others_total_ratings'] ?? 0) + ($frndQuestion['self_total_ratings'] ?? 0);
                if ($totalFriendRatings > 0) {
                    $frndPercentage = round(($totalFriendPoints / ($totalFriendRatings * 5)) * 100);
                }
            }

            $getStatus = function($percentage) {
                if ($percentage === null) return '';
                return match (true) {
                    $percentage >= 84 => 'Perfect',
                    $percentage >= 60 => 'Very Good',
                    $percentage >= 40 => 'Good',
                    $percentage > 0 => 'Poor',
                    default => '',
                };
            };

            $format = fn($percentage) => $percentage === null ? '' : $getStatus($percentage) . ', ' . $percentage . '  ';

            $selfPoints = $selfQuestion['self_total_points'] ?? 0;
            $selfRatings = $selfQuestion['self_total_ratings'] ?? 0;
            $totalPoints = $selfPoints + $totalFamilyPoints + $totalFriendPoints;
            $totalRatings = $selfRatings + $totalFamilyRatings + $totalFriendRatings;

            $avgPercentage = ($totalRatings > 0) ? round(($totalPoints / ($totalRatings * 5)) * 100) : null;
        @endphp

        <tr>
          <td>{{ $text ?: 'Question ' . $question['question_id'] }}</td>
          <td>{{ $avgPercentage ? $getStatus($avgPercentage) . ', ' . $avgPercentage . '  ' : '' }}</td>
          <td>{{ $format($selfPercentage) }}</td>
          <td>{{ $format($famPercentage) }}</td>
          <td>{{ $format($frndPercentage) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

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



