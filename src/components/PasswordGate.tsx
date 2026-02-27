import { useState, useCallback, useEffect, type ReactNode } from "react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

const STORAGE_KEY = "ccspro_dev_auth";

const PasswordGate = ({ children }: { children: ReactNode }) => {
  const devPassword = import.meta.env.VITE_DEV_PASSWORD as string | undefined;
  const isGateEnabled = Boolean(devPassword && devPassword.trim() !== "");

  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const [isUnlocked, setIsUnlocked] = useState(false);

  const checkStoredAuth = useCallback(() => {
    if (!isGateEnabled) return true;
    try {
      const stored = localStorage.getItem(STORAGE_KEY);
      if (!stored) return false;
      return stored === "authenticated";
    } catch {
      return false;
    }
  }, [isGateEnabled, devPassword]);

  useEffect(() => {
    if (isGateEnabled && checkStoredAuth()) {
      setIsUnlocked(true);
    }
  }, [isGateEnabled, checkStoredAuth]);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setError("");
    if (password === devPassword) {
      try {
        localStorage.setItem(STORAGE_KEY, "authenticated");
        setIsUnlocked(true);
      } catch {
        setError("Could not save access.");
      }
    } else {
      setError("Incorrect password.");
    }
  };

  if (!isGateEnabled) {
    return <>{children}</>;
  }

  if (isUnlocked) {
    return <>{children}</>;
  }

  return (
    <div className="fixed inset-0 z-[9999] flex items-center justify-center bg-background">
      <form
        onSubmit={handleSubmit}
        className="w-full max-w-sm space-y-4 rounded-lg border border-border bg-card p-6 shadow-lg"
      >
        <div className="space-y-2">
          <Label htmlFor="dev-password">Enter access password</Label>
          <Input
            id="dev-password"
            type="password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            placeholder="Password"
            autoFocus
            autoComplete="current-password"
            className="w-full"
          />
        </div>
        {error && (
          <p className="text-sm text-destructive" role="alert">
            {error}
          </p>
        )}
        <Button type="submit" className="w-full">
          Continue
        </Button>
      </form>
    </div>
  );
};

export default PasswordGate;
