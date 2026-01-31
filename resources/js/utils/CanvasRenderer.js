/**
 * CanvasのCoreクラス
 * - canvas/context の管理
 * - クリア処理
 * - 座標変換
 * - 画像描画
 * - テキスト描画
 * - パス描画
 */
export class CanvasRenderer {
    // 画面のサイズ
    #canvas;
    #width;
    #height;
    #ctx;
    #fontSize = '50px';
    #clientRect;

    /**
     * コンストラクタ
     */
    constructor(id = 'canvas') {
        this.#init(id);
    }

    /**
     * 初期化メソッド
     * @param string CanvasのID
     */
    #init(id) {
        this.#canvas = document.getElementById(id);

        // Canvasの存在の確認
        if (!this.#canvas) {
            throw new Error(`Canvas element '${id}' が見つかりません`);
        }

        this.#width = this.#canvas.width;
        this.#height = this.#canvas.height;
        this.#ctx = this.#canvas.getContext('2d');
        this.#ctx.font = `${this.#fontSize} Arial sans-serif`;
        this.#ctx.fillStyle = 'black';
        this.#ctx.textAlign = 'center';
        this.#ctx.textBaseline = 'middle';
        this.#clientRect = this.#canvas.getBoundingClientRect();
    }

    /**
     * Canvasに指定の文字を描画する
     * @param {string} text 
     * @param {integer} width 
     * @param {integer} height 
     */
    drawText(text, width, height) {
        this.#ctx.fillText(text, width, height);
    }

    /**
     * Canvasに四角形を描画する
     * @param {integer} rectX 
     * @param {integer} rextY 
     * @param {integer} width 
     * @param {integer} height 
     */
    drawRect(rectX, rextY, width, height) {
        this.#ctx.strokeRect(rectX, rextY, width, height);
    }

    /**
     * Canvasの四角形を指定して
     * @param {array} rect 
     */
    clearRect(rect) {
        this.#ctx.clearRect(rect.x, rect.y, rect.width, rect.height);
    }

    /**
     * Canvasを全てクリアする
     */
    clearAll() {
        this.#ctx.clearRect(0, 0, this.#width, this.#height);
    }

    get width() {
        return this.#width;
    }

    get height() {
        return this.#height;
    }

    get canvas() {
        return this.#canvas;
    }

    get ctx() {
        return this.#ctx;
    }

    get clientRect() {
        return this.#clientRect;
    }
}
