<!-- KrispiKas - Custom Confirm & Prompt Modals -->
<!-- Menggantikan confirm() dan prompt() bawaan browser yang sering di-block -->

<!-- Custom Confirm Dialog -->
<div id="modalConfirm" class="fixed inset-0 z-[200] hidden bg-black/60 items-center justify-center p-4 backdrop-blur-sm modal-overlay">
    <div class="bg-white w-full max-w-xs rounded-3xl shadow-2xl relative p-6 space-y-4 m-2 modal-content text-center">
        <div class="text-4xl" id="confirmIcon">⚠️</div>
        <p id="confirmMessage" class="text-sm font-bold text-gray-800 leading-relaxed"></p>
        <div class="grid grid-cols-2 gap-3 pt-2">
            <button type="button" id="confirmBtnBatal" onclick="resolveConfirm(false)" 
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 p-3 font-bold rounded-2xl text-sm cursor-pointer transition-all btn-press">
                Batal
            </button>
            <button type="button" id="confirmBtnOk" onclick="resolveConfirm(true)" 
                class="bg-red-600 hover:bg-red-700 text-white p-3 font-bold rounded-2xl text-sm cursor-pointer transition-all btn-press">
                Ya, Lanjut
            </button>
        </div>
    </div>
</div>

<!-- Custom Prompt Dialog -->
<div id="modalPrompt" class="fixed inset-0 z-[200] hidden bg-black/60 items-center justify-center p-4 backdrop-blur-sm modal-overlay">
    <div class="bg-white w-full max-w-xs rounded-3xl shadow-2xl relative p-6 space-y-4 m-2 modal-content">
        <div class="text-center">
            <div class="text-4xl">📝</div>
            <p id="promptMessage" class="text-sm font-bold text-gray-800 mt-2 leading-relaxed"></p>
        </div>
        <input type="text" id="promptInput" 
            class="w-full p-3 border-2 border-amber-500 rounded-2xl text-center font-bold text-base focus:outline-none focus:ring-2 focus:ring-amber-400"
            placeholder="Ketik di sini...">
        <div class="grid grid-cols-2 gap-3 pt-1">
            <button type="button" onclick="resolvePrompt(null)" 
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 p-3 font-bold rounded-2xl text-sm cursor-pointer transition-all btn-press">
                Batal
            </button>
            <button type="button" onclick="resolvePrompt(document.getElementById('promptInput').value)" 
                class="bg-amber-600 hover:bg-amber-700 text-white p-3 font-bold rounded-2xl text-sm cursor-pointer transition-all btn-press">
                Simpan
            </button>
        </div>
    </div>
</div>
