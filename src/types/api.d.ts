export interface ApiResponse<T = any> {
  status: string
  data: T
  message?: string
}

export interface PaginatedResponse<T> {
  data: T[]
  meta: {
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
}

export interface Employee {
  id: number
  name: string
  sex: string
  category: string
  echelon: string
  department: string
  position: string
  birthDate: string
  hireDate: string
  endDate?: string
  address: string
  contact: string
  rib: string
  status: string
}

export interface LeaveRequest {
  id: number
  employee: string
  type: string
  startDate: string
  endDate: string
  reason: string
  status: string
  hrComment?: string
}

export interface Document {
  id: number
  title: string
  description: string
  category: string
  file_path: string
  created_at: string
  updated_at: string
}

export interface Training {
  id: number
  title: string
  description: string
  category: string
  duration: string
  level: string
  startDate: string
  maxParticipants: number
  participants: number
  video_url?: string
  materials?: string[]
}

export interface Evaluation {
  id: number
  employee: string
  department: string
  period: string
  scores: {
    objectives: number
    skills: number
    values: number
  }
  comments: {
    strengths: string
    improvements: string
    goals: string
  }
  totalScore: number
  status: string
  created_at: string
}