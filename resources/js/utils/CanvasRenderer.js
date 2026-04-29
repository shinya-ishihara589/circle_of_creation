/**
 * CanvasのCoreクラス
 */
export class CanvasRenderer {
    #canvas;
    #ctx;
    #width;
    #height;

    /**
     * @param {string} id Canvas要素のID
     */
    constructor(id = 'canvas') {
        this.#canvas = document.getElementById(id);

        if (!this.#canvas) {
            throw new Error(`Canvas element '${id}' が見つかりません`);
        }

        this.#ctx = this.#canvas.getContext('2d');
        this.#width = this.#canvas.width;
        this.#height = this.#canvas.height;

        this.#initStyle();
    }

    /**
     * デフォルトの描画スタイルを設定
     */
    #initStyle() {
        this.#ctx.font = '50px Arial, sans-serif';
        this.#ctx.fillStyle = 'black';
        this.#ctx.strokeStyle = 'black'; // 枠線の色
        this.#ctx.textAlign = 'center';
        this.#ctx.textBaseline = 'middle';
    }

    /**
     * テキストを描画
     * @param {string} text 描画する文字
     * @param {number} x X座標
     * @param {number} y Y座標
     */
    drawText(text, x, y) {
        this.#ctx.fillText(text, x, y);
    }

    /**
     * 矩形の枠線を描画
     * @param {number} x 開始X座標
     * @param {number} y 開始Y座標
     * @param {number} w 幅
     * @param {number} h 高さ
     */
    drawRect(x, y, w, h) {
        this.#ctx.strokeRect(x, y, w, h);
    }

    /**
     * 指定範囲を消去
     * @param {Object} rect {x, y, width, height} を持つオブジェクト
     */
    clearRect({ x, y, width, height }) {
        this.#ctx.clearRect(x, y, width, height);
    }

    /**
     * Canvas全体をクリア
     */
    clearAll() {
        this.#ctx.clearRect(0, 0, this.#width, this.#height);
    }

    /* --- ゲッター --- */

    get width() { return this.#width; }
    get height() { return this.#height; }
    get ctx() { return this.#ctx; }
    get canvas() { return this.#canvas; }

    /**
     * Canvasの表示上の位置とサイズを取得（クリック判定用）
     */
    get clientRect() {
        return this.#canvas.getBoundingClientRect();
    }
}
