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
            <div class="mt-2">
                <a href="{{ route('survey.export', ['id' => $surveyId]) }}" class="btn btn-primary">Download Excel</a>
            </div>
          </div>
        @endif 
      @endforeach
    </div>
    




   <div id="textshaped" style="display: block;">
    <h1>Title</h1>

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

    <p><strong>Sonuç:</strong> Bay/Ms. Smith, kusursuz mentorluk becerileri ve geniş iş deneyimiyle öne çıkan biridir. Empati, iletişim ve motivasyon konularında son derece başarılı olan Smith, danışanlarının başarılarını artırmak için etkili bir rehberdir. Devam eden destek ve gelişimle, Smith’in mentorluk hizmetlerinin daha da etkili hale geleceğine inanıyoruz.</p>
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
    <!-- PDF download (only for text view) -->
    <form action="{{ route('report.download') }}" method="GET" id="pdfForm">
        <input type="hidden" name="type" value="text">
        <button type="submit" class="btn" style="background-color: #8CB368; color: white;">
            Download PDF
        </button>
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
          const pdfForm = document.getElementById('pdfForm');
  
          function toggleView() {
              const isBar = barRadio.checked;
  
              barDiv.style.display = isBar ? 'block' : 'none';
              textDiv.style.display = isBar ? 'none' : 'block';
  
              excelForm.style.display = isBar ? 'inline-block' : 'none';
              pdfForm.style.display = isBar ? 'none' : 'inline-block';
          }
  
          toggleView();
          barRadio.addEventListener('change', toggleView);
          textRadio.addEventListener('change', toggleView);
      });
  </script>
  
  
  
      
      
</x-app-layout>



