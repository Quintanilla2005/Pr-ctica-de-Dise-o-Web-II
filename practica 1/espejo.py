"""
Espejo GUI para la función reloj_espejo
- Ejecutar: python espejo_gui.py  (abre la ventana)
- La función reloj_espejo puede importarse sin lanzar la GUI (útil para pruebas).

Interfaz: campo de texto HH:MM, botón Calcular, botones de ejemplo, resultado y botón para copiar al portapapeles.

Hecho usando Tkinter (incluido en Python estándar).
"""

import re
import tkinter as tk
from tkinter import ttk, messagebox

def reloj_espejo(tiempo: str) -> str:
    """Devuelve la hora "real" espejo dada una hora en formato HH:MM.
    Mantengo la lógica original del script provisto.
    """
    partes_divididas = tiempo.split(":")
    h, m = int(partes_divididas[0]), int(partes_divididas[1])
    
    if m == 0:
        hora_real = (12 - h) 
        minutos_reales = 0
    else:
        hora_real = (11 - h)
        minutos_reales = 60 - m

    return f"hora real: {hora_real:02d}:{minutos_reales:02d}"


# --- GUI ---
class EspejoApp(tk.Tk):
    def __init__(self):
        super().__init__()
        self.title("Reloj Espejo")
        self.resizable(False, False)
        self.create_widgets()

    def create_widgets(self):
        pad = 8
        frm = ttk.Frame(self, padding=pad)
        frm.grid(row=0, column=0, sticky="nsew")

        ttk.Label(frm, text="Hora (HH:MM):").grid(row=0, column=0, sticky="w")
        self.entry = ttk.Entry(frm, width=10)
        self.entry.grid(row=0, column=1, sticky="w")
        self.entry.focus()

        btn_calc = ttk.Button(frm, text="Calcular", command=self.on_calcular)
        btn_calc.grid(row=0, column=2, padx=(8, 0))

        # Resultado
        ttk.Label(frm, text="Resultado:").grid(row=1, column=0, pady=(6,0), sticky="w")
        self.result_var = tk.StringVar(value="")
        self.result_label = ttk.Label(frm, textvariable=self.result_var, font=(None, 11, 'bold'))
        self.result_label.grid(row=1, column=1, columnspan=2, pady=(6,0), sticky="w")

        # Botones ejemplo
        ejemplos = ["01:45", "10:00", "03:25", "11:11"]
        ejemplos_frame = ttk.Frame(frm)
        ejemplos_frame.grid(row=2, column=0, columnspan=3, pady=(10,0))
        ttk.Label(ejemplos_frame, text="Ejemplos:").grid(row=0, column=0, sticky="w")
        for i, ex in enumerate(ejemplos):
            b = ttk.Button(ejemplos_frame, text=ex, width=8, command=lambda e=ex: self.fill_and_calc(e))
            b.grid(row=0, column=i+1, padx=4)

        # Copiar
        btn_copy = ttk.Button(frm, text="Copiar resultado", command=self.copy_result)
        btn_copy.grid(row=3, column=0, columnspan=3, pady=(10,0))

        # Bind Enter
        self.bind('<Return>', lambda e: self.on_calcular())

    def validate_time(self, texto: str) -> bool:
        # Formato simple HH:MM y valores razonables
        if not re.match(r'^\d{1,2}:\d{2}$', texto):
            return False
        h, m = map(int, texto.split(':'))
        if not (0 <= m < 60):
            return False
        if not (0 <= h <= 12):  # asumo reloj de 12 horas
            return False
        return True

    def on_calcular(self):
        texto = self.entry.get().strip()
        if not self.validate_time(texto):
            messagebox.showerror("Entrada inválida", "Introduce la hora en formato HH:MM (hora 0-12, minutos 00-59). Ej.: 01:45")
            return
        try:
            res = reloj_espejo(texto)
        except Exception as e:
            messagebox.showerror("Error", f"Ocurrió un error al calcular: {e}")
            return
        self.result_var.set(res)

    def fill_and_calc(self, ejemplo: str):
        self.entry.delete(0, tk.END)
        self.entry.insert(0, ejemplo)
        self.on_calcular()

    def copy_result(self):
        res = self.result_var.get()
        if not res:
            messagebox.showinfo("Nada que copiar", "No hay resultado para copiar.")
            return
        self.clipboard_clear()
        self.clipboard_append(res)
        messagebox.showinfo("Copiado", "Resultado copiado al portapapeles.")


if __name__ == '__main__':
    # Si se ejecuta directamente: lanzar la app
    app = EspejoApp()
    app.mainloop()